<?php

namespace App\Http\Controllers\Reports;

use DateInterval;
use App\carrier;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\replenishment;
use App\order;
use App\store;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;

class DaliaReports extends Controller
{
    public function addReplanchment()
    {
        $stores = store::all();
        return view('newDesign.mainPage.addReplanchment', ['stores' => $stores]);
    }

    public function get_Replanchment(Request $request)
    {

        if ($request->type == 0) {
            $url = 'https://epsilonintegration.shipedge.com/API/Rest/v3/Replenishment/get_reple/';
            $url = $url . $request->rep_id . '/isnaad';
            $repla = replenishment::where([['rep_id', $request->rep_id], ['account_id', $request->account_id]])->first();
            if ($repla) {
                return response()->json([
                    'status' => false,

                    'message' => [

                        'description' => [
                            'rep_id' => ['this replenishment already  entered ']
                        ]

                    ]


                ]);
            }
            $validate = \validator($request->all(), [
                'account_id' => 'required',
                'rep_id' => 'required',
                'date' => 'required|date',
                'time' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => [
                        "description" => $validate->errors()
                    ]
                ]);
            }

            $timeStamp = Carbon::now()->getTimestamp();
            $api_key = store::where('account_id', $request->account_id)->first();
            $str = str_replace(PHP_EOL, '', $api_key->api_key);

            $client = $client = new GuzzleHttpClient(['verify' => false]);
            $headers = [

                'X-UID' => $request->account_id,
                'X-TIMESTAMP' => $timeStamp,
                'X-HASH' => sha1($request->account_id . $timeStamp . $str),
                "Content-Type" => "application/json"

            ];

            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'body' => json_encode(['key' => $api_key->api_key]),
                'debug' => true
            ]);
            $res = json_decode($response->getBody()->getContents());
//dd($res);
            if ($res->success == false) {
                return response()->json([
                    'status' => false,

                    'message' => [

                        'description' => [
                            'rep_id' => ['pleas enter a valid replenishment id or store']
                        ]

                    ]


                ]);
            }

            $items = $res->data->items;
            $quantity_recived = 0;
            $quantity_request = 0;

            foreach ($items as $item) {
                $quantity_recived = $quantity_recived + $item->qty_received;
                $quantity_request = $quantity_request + $item->qty_request;

            }

            replenishment::create([
                'rep_id' => $request->rep_id,
                'remaining' => $quantity_request - $quantity_recived,
                'quantity_recived' => $quantity_recived,
                'quantity_request' => $quantity_request,
                'account_id' => $request->account_id,
                'date' => $request->date != null ? $request->date : date('yy-m-d'),
                'time' => $this->time_change($request->time),
                'time_type' => 'am',
                'rep_type' => 0
            ]);
            return response()->json([
                'status' => true,
                'qty_rec' => $quantity_recived,
                'qty_req' => $quantity_request,
                'remaining' => $quantity_request - $quantity_recived,
                'client' => $api_key->name,
                'rep_id' => $request->rep_id,
                'type' => 0
            ]);
        } else {
            $validate = \validator($request->all(), [
                'account_id' => 'required',
                'date' => 'required|date',
                'pallets' => 'required',
            ]);
            if ($validate->fails())
                return response()->json([
                    "status" => false,
                    "message" => [
                        "description" => $validate->errors()
                    ]
                ]);
            $time = substr($this->time_change($request->time), 0, -2);

            replenishment::create([

                'account_id' => $request->account_id,
                'date' => $request->date != null ? $request->date : date('yy-m-d'),
                'time' => $time,
                'time_type' => 'am',
                'pallets' => $request->pallets,
                'rep_type' => 1
            ]);
            return response()->json([
                'status' => true,
                'type' => 1

            ]);
        }
    }


    public function dailay_Report(Request $request, $flag = false)
    {
        $rep = replenishment::query();

        if ($request->has('date')) {

            $rep->whereDate('date', $request->date)->orWhere('is_end', 0);;
//    DB::raw('sum(time_to_sec(timediff(shiping_date_time ,created_at)) )')
            $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty')

            )->selectRaw('sum(time_to_sec(timediff( created_at,printed_time))/3600 ) as leadtime')
                ->whereDate('shipping_date', $request->date)
                ->with('store')
                ->groupBy('store_id')
                ->get();

        } else {
            $rep->whereDate('date', Carbon::today())->orWhere('is_end', 0);

            $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty'))
                ->selectRaw('sum(time_to_sec(timediff( created_at,printed_time))/3600 ) as leadtime')
                ->whereDate('shipping_date', Carbon::today())
                ->with('store')
                ->groupBy('store_id')
                ->get();
        }
        $array = $orders_shipped->groupBy('store.name');

        $rep = $rep->with('store')->get();
        $totalOrders = $orders_shipped->sum(function ($item) {
            return $item->total;
        });
        $totalQty = $orders_shipped->sum(function ($item) {
            return $item->Qty;
        });
        $Total_lead_time = $orders_shipped->sum(function ($order) {
            return $order->leadtime;
        });

        if ($flag) {
            return [
                'rep' => $rep,
                'orders_shipped' => $orders_shipped,
                'totalOrders' => $totalOrders,
                'totalQty' => $totalQty,
                'store' => $array,

                'lead_time' => number_format($Total_lead_time / $totalOrders * 60, 2, ',', ' ') . ' M'
            ];
        } else {

            return view('m_design.dailyReport', [
                'rep' => $rep,
                'orders_shipped' => $orders_shipped,
                'totalOrders' => $totalOrders,
                'totalQty' => $totalQty,
                'lead_time' => number_format($Total_lead_time / ($totalOrders + 1), 2, ',', ' ')

            ]);
        }
    }


    public function update_Replanchment()
    {
        $reps = replenishment::where([['is_end', 0], ['rep_type', '0']])->get();

        foreach ($reps as $rep) {


            $timeStamp = Carbon::now()->getTimestamp();
            $api_key = store::where('account_id', $rep->account_id)->first();
            $url = 'https://epsilonintegration.shipedge.com/API/Rest/v3/Replenishment/get_reple/';
            $url = $url . $rep->rep_id . '/isnaad';
            $str = str_replace(PHP_EOL, '', $api_key->api_key);
            $client = $client = new GuzzleHttpClient();
            $headers = [

                'X-UID' => $api_key->account_id,
                'X-TIMESTAMP' => $timeStamp,
                'X-HASH' => sha1($api_key->account_id . $timeStamp . $str),
                "Content-Type" => "application/json"

            ];

            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'body' => json_encode(['key' => $api_key->api_key]),
                //'debug' => true
            ]);

            $res = json_decode($response->getBody()->getContents());

            $is_end = 0;
            if (strcmp($res->data->status, 'ready for your review') == 0 || $res->data->status == "") {
                $rep->last_update = Carbon::now('Asia/Riyadh')->format('Y-m-d H:i:s');
                $is_end = 1;
            }

            $items = $res->data->items;
            $quantity_recived = 0;
            $quantity_request = 0;

            foreach ($items as $item) {
                $quantity_recived = $quantity_recived + $item->qty_received;
                $quantity_request = $quantity_request + $item->qty_request;

            }

            if ($rep->quantity_recived < $quantity_recived) {
                $remaining = $quantity_request - $quantity_recived;
                $rep->recieved_befor = $rep->quantity_recived;
                $rep->remaining = $remaining;
                $rep->quantity_request = $quantity_request;
                $rep->quantity_recived = $quantity_recived;

            }
            $rep->is_end = $is_end;
            $rep->save();


        }
    }

    public function get_dailay_report_aj(Request $request)
    {
        $ar = $this->dailay_Report($request, true);

        return response()->json([
            'data' => $ar
        ]);
    }

    public function carrier_daliay(Request $request)
    {
        if ($request->has('date')) {
            $ca = carrier::withCount(['orders' => function ($q) use ($request) {
                $q->where('shipping_date', $request->date);
            }])->get();

        } else {
            $ca = carrier::withCount(['orders' => function ($q) {
                $q->where('shipping_date', Carbon::now()->format('Y-m-d'));
            }])->get();

        }

        $ca = $ca->filter(function ($carrier) {

            return $carrier->orders_count > 0;
        });

        $ca = $ca->map(function ($carrier) {
            return [
                'carrier' => $carrier->name,
                'ordre_count' => $carrier->orders_count
            ];
        });

        return response()->json($ca);

    }

    private function time_change($time)
    {
        return date("H:i", strtotime($time));

    }

    public function replancment_show()
    {
        $stores = store::all();

        return view('m_design.replanchments.replanchemnt', ['sotres' => $stores]);
    }

    public function get_rep(Request $request)
    {
        $replancments = replenishment::query()->with('store:account_id,name');

        if ($request->has('store') && $request->get('store') != '') {
            $replancments = $replancments->where('account_id', $request->get('store'));

        }
        if ($request->has('from') && $request->get('from') != '') {


            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                if ($request->get('dateType') == 0) {
                    $to = $to->add(new DateInterval('P1D'));
                }
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $replancments->whereBetween('created_at', [$from, $to]);

            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $replancments->whereBetween('created_at', [$from, $to]);
            }
        }

        // dd($replancments->limit(5)->get());

        return DataTables::of($replancments)
            ->addColumn('action', function ($rep) {
                return '<div class="btn-group" role="group">
																	<button id="btnGroupDrop1" type="button" class="btn btn-dark font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">action</button>
																	<div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
																		<a class="dropdown-item" href="#"  onclick="edit(' . $rep->id . ')"><span class="navi-icon">
																	<i class="fas fa-edit"></i></span>
                        <span class="navi-text">edit</span> </a>
																		<a class="dropdown-item" href="#"><span class="navi-icon">
																	<i class="fas fa-trash-alt"></i></span>
                        <span class="navi-text">delete</span></a>
																	</div>
																</div>';


            })->rawColumns(['action'])
            ->make(true);
    }

    public function getDailayReport(Request $request)
    {
        $rep = replenishment::query();

        if ($request->has('date')) {

            $rep->whereDate('date', $request->date)->orWhere('is_end', 0);

        } else {
            $rep->whereDate('date', Carbon::today())->orWhere('is_end', 0);
        }

        return response()->json([
            'rep' => $rep->with('store')->get(),
            'orders_shipped' => $sh_order = $this->shippedOrder($request),
            'sh_order' => $sh_order->map(function ($item) {
                return [
                    'order_number' => $item->total,
                    'store' => $item->store->name
                ];
            })
        ]);

    }

    private function shippedOrder(Request $re)
    {

        if ($re->has('date') && $re->get('date') != '') {

            $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty'))
                ->selectRaw('avg(time_to_sec(timediff(printed_time,created_at )))/60 as leadtime')
                ->whereDate('shipping_date', $re->date)
                ->with('store')
                ->groupBy('store_id')
                ->get();
        } else {
            $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty'))
                ->selectRaw('avg(time_to_sec(timediff(printed_time,created_at )))/60 as leadtime')
                ->whereDate('shipping_date', Carbon::now())
                ->with('store')
                ->groupBy('store_id')
                ->get();
        }

        return $orders_shipped;
    }

    public function get_replanchment_for_update(Request $request)
    {
        $rep = replenishment::find($request->id);
        if ($rep) {
            return response()->json([
                'status' => true,
                'replanchment' => $rep
            ]);
        } else {
            return response()->json([
                'status' => false,

            ]);
        }
    }

    public function replanchment_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'id' => 'required',
            'store' => 'required',
            'date' => 'required',
            'rep_id'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['er'=>$validator->messages(),'status'=>false]);
    }else{
         $rep=replenishment::find($request->id);
            $rep->rep_id=$request->rep_id;
            $rep->date=$request->date;
            $rep->account_id=$request->store;
            $rep->save();
            return response()->json(['status'=>true]);
        }
    }
     public function deleteReplanchment(Request $request){

        replenishment::find($request->id)->delete();
        return response()->json([
           'status'=>true
        ]);
    }

}
