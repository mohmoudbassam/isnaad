<?php

namespace App\Http\Controllers\Client;

use App\carrier;
use App\Http\Controllers\Controller;
use App\Models\order_cancel;
use App\Models\replenishment;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Yajra\DataTables\DataTables;
use App\Exports\Client\CodReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\store_cancel;
class dailayReportController extends Controller
{
 public function index(){
     return view('newDesign.Client.mainPage.daily');
 }




    public function dailay_Report(Request $request, $flag = false)
    {

       $rep = replenishment::query();

        if ($request->has('date')) {

            $rep->whereDate('date', $request->date)->orWhere('is_end', 0)->where('account_id',auth()->user()->store->account_id);
       
//    DB::raw('sum(time_to_sec(timediff(shiping_date_time ,created_at)) )')
            $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty')

            )->selectRaw('sum(time_to_sec(timediff(shiping_date_time ,created_at))/3600 ) as leadtime')
                ->whereDate('shipping_date', $request->date)
                ->where('store_id',auth()->user()->store->account_id)
                ->with('store')
                ->groupBy('store_id')
                ->get();
           $carriers=order::query()->select('carrier')
               ->selectRaw('count(*) as car')
               ->where('store_id',auth()->user()->store->account_id)
               ->whereDate('shipping_date', $request->date)
               ->groupBy('carrier')->get();
        } else {
            $rep->whereDate('date', Carbon::today())->orWhere('is_end', 0)->where('account_id',auth()->user()->store->account_id);

            $orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty'))
                ->selectRaw('sum(time_to_sec(timediff(shiping_date_time ,created_at))/3600 ) as leadtime')
                ->whereDate('shipping_date', Carbon::today())
                ->where('store_id',auth()->user()->store->account_id)
                ->with('store')
                ->groupBy('store_id')
                ->get();
            $carriers=order::query()->select('carrier')
                ->where('store_id',auth()->user()->store->account_id)
                ->whereDate('shipping_date', $request->date)
                ->groupBy('carrier')->get();
        }
        $array= $orders_shipped->groupBy('store.name');

      //  $rep = $rep->get();
        $totalOrders = $orders_shipped->sum(function ($item) {
            return $item->total;
        });
        $totalQty = $orders_shipped->sum(function ($item) {
            return $item->Qty;
        });
        $Total_lead_time= $orders_shipped->sum(function ($order){
            return  $order->leadtime ;
        });

        if ($flag) {
           
            return [
                'rep' => $rep->get(),
                'orders_shipped' => $orders_shipped,
                'totalOrders' => $totalOrders+1,
                'totalQty' => $totalQty,
                'store'=>$array,
                'carriers'=>$carriers,

                'lead_time'=>number_format($Total_lead_time/($totalOrders+1),2, ',', ' ')
            ];

        } else {
            return view('newDesign.mainPage.dailayReport');
        }
    }
    public function get_dailay_report_aj(Request $request)
    {
        $ar=$this->dailay_Report($request,true);

        return response()->json([
            'data'=>  $ar
        ]);
    }


}
