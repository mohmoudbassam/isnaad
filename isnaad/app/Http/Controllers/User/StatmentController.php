<?php

namespace App\Http\Controllers\User;

use App\Exports\BillingExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\payments;
use App\statment;
use App\statment_file;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Tag\P;
use Yajra\DataTables\DataTables;
use Exception;
use App\user;
class StatmentController extends Controller
{

    public function index()
    {

        $store = store::all();
        $account_mangers = user::query()->whereIn('id', store::select('account_manger')->whereNotNull('account_manger')->groupBy('account_manger')->get()->pluck('account_manger'))->get();

        return view('m_design.statment.statment',  ['sotres' => $store,'account_mangers'=>$account_mangers]);
    }

    public function add()
    {
        $store = store::all();

        return view('newDesign.statment.add_statment', ['sotres' => $store]);
    }

    public function store(Request $request)
    {


        $rule = [
            'description_from_date' => 'required|date',
            'description_to_date' => 'required|after_or_equal:description_from_date',
            // 'statment_date' => 'required|date',
            // 'initial_date' => 'required|date',
            'last_date' => 'required|date',
            'edit' => 'required',
            'account_id' => 'required',
            'inv' => 'required|unique:statment',
            //   'files.*' => 'required|max:10000|mimes:xlsx,pdf',
            'cod' => 'required|numeric',

        ];
        $message = [
            'after_or_equal' => 'the date description not valid',
            'paid.required' => 'pleas choies the type',
            'account_id.required' => 'pleas choies the store'
        ];
        $vl = Validator::make($request->all(), $rule, $message);
        if ($vl->fails()) {
            return redirect()->back()->withErrors($vl->errors());
        }



        $statment = statment::create($request->all());
        if (!$statment) {
            return redirect()->back()->withErrors('error', 'pleas try again');
        }

        if($request->payment){

            foreach($request->payment as $key=>$payment){
                if($payment){
                    payments::create([
                        'payment' => $payment,
                        'date' => Carbon::now()->format('Y-m-d'),
                        'invoice_id' => $key,
                        'type'=>3,
                        'from_invoice'=>$statment->id
                    ]);
                }
                $lastStatment=statment::find($statment->id);
                if ($lastStatment->net_blance == 0) {
                    $lastStatment->update([
                        'paid' => 1
                    ]);
                } else {
                    $lastStatment->update([
                        'paid' => 0
                    ]);
                };
            }

        }
        if ($statment->net_blance == 0) {
            $statment->update([
                'paid' => 1
            ]);
        } else {
            $statment->update([
                'paid' => 0
            ]);
        }
        if ($request->has('files')) {

            foreach ($request->file('files') as $file) {
                $real_name = $file->getClientOriginalName();
                $store_Name = time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move('statment/', $store_Name);
                $sf = statment_file::create(['statment_id' => $statment->id, 'store_name' => $store_Name, 'real_name' => $real_name]);

                $data[] = $store_Name;
            }

        }
        session()->put('added', 'statment adede successfully');
        return redirect()->route('ne-show-statment', $statment->id);

    }

    public function get_statment(Request $request, $flag = false)
    {
        DB::listen(function ($q){
            Log::info($q->sql);
        });
        $statments = statment::query()->netBalance()->with(['acount', 'file']);

        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));

                $from = new \DateTime($request->get('from'));

                $statments = $statments->whereBetween('description_from_date', [$from, $to]);
            } else {

                $from = new \DateTime($request->get('from'));
                // dd($from);
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                //  dd($to);
                $statments = $statments->whereBetween('description_from_date', [$from, $to]);
            }
        }
        if ($request->has('paid') && $request->get('paid') != '') {
            $statments = $statments->where('paid', $request->get('paid'));
        }

        if ($request->has('account_id') && $request->get('account_id') != '') {
            $statments = $statments->where('account_id', $request->get('account_id'));
        }

        if ($request->has('account_manger') && $request->get('account_manger') != '0') {
            if ($request->get('account_manger') == 1) {

                $statments= $statments->whereHas('acount',function($q)use($request){
                    $q->whereNull('account_manger');
                });
            } else {

                $statments= $statments->whereHas('acount',function($q)use($request){
                    $q->where('account_manger',$request->get('account_manger'));
                });
            }

        }

        $statments->when($request->from_amount,function($q)use($request){
            $q->whereRaw('total_amount Between ? and ?',[$request->from_amount,$request->to_amount]) ;
        });
        $st = clone $statments;
        $statment_to_get_netBlance = clone $statments;
        $stToNetBlance = clone $statments;
        $stmt = $st->select(DB::raw('sum(cod) as codSumation , sum(total_amount) as isnaadInvoice'), 'statment.*')->get();
        $sumNetBlance = $stToNetBlance->get()->reduce(function ($netBlancec, $s) {

            if ($s->paid == 0) {
                try {
                    $net = str_replace(',', '', $s->net_balance);
                    return $net + $netBlancec;
                } catch (Exception $e) {
                    dd($s->net_blance, $netBlancec, $e);
                }


            } else {
                return $netBlancec + 0;
            }

        }, 0);
        $sumNetBlance = number_format($sumNetBlance,2, ',', '');

        if ($flag) {
            return $statments;
        } else {

            return Datatables::of($statments)
                ->addColumn('select', function ($statmet) {
                    return '
                     <label class="checkbox checkbox-rounded">
                        <input type="checkbox" value="' . $statmet->id . '" class="select" id="' . $statmet->id . '" name="select[]">
                          <span></span></label>
                   ';


                })
                ->rawColumns(['select'])->with(['cod' => $stmt[0]->codSumation, 'isnaadInvoice' => $stmt[0]->isnaadInvoice, 'sumNetBlance' => $sumNetBlance])
                ->make(true);
        }

    }

    public function show_statment($id)
    {
        $statment = statment::query()->whth('payments')->where('id', $id)->first();

        if (!$statment) {

            return redirect()->back();
        }
        $statment = statment::query()->where('id', $id)->with('acount')->first();

        $store = store::all();
        return view('newDesign.statment.show_statment', ['statemnt' => $statment, 'sotres' => $store]);
    }

    public function download_file($id)
    {
        $file = statment_file::find($id);
        $path = 'statment/' . $file->store_name;

        return response()->download($path,$file->real_name);

    }

    public function delete_file($id)
    {
        $file = statment_file::find($id);
        $file->delete();

        return redirect()->back();
    }

    public function update_statment(Request $request, $id)
    {
        $statment = statment::query()->where('id', $id)->first();

        $rule = [

            'last_date' => 'required|date',

            'account_id' => 'required',
            'files.*' => 'required|max:50000|mimes:xlsx,pdf',


            'cod' => 'required',

        ];
        $message = [
            'after_or_equal' => 'the date description not valid',
            'paid.required' => 'pleas choies the type',
            'account_id.required' => 'pleas choies the store'
        ];
        $vl = Validator::make($request->all(), $rule, $message);

        if ($vl->fails()) {
            return redirect()->back()->withErrors($vl->errors());
        }
        $request->merge([
            'total_amount' => $this->udateVaueBeforUpdate($request->total_amount),
            'balance' => $this->udateVaueBeforUpdate($request->balance),
            'cod' => $this->udateVaueBeforUpdate($request->cod),

        ]);


        $statment->update($request->all());
        //created payments

        if($request->payments){
            //   dd($request->payments,'create');
            foreach ($request->payments as $payment) {
                if ($payment['payment'] && $payment['date'] ) {

                    payments::create([

                        'payment' => $payment['payment'],
                        'date' => $payment['date'],
                        'invoice_id' => $statment->id,
                        'type' => $payment['type'],
                        'from_invoice' => $payment['deduct_invoice'] ?? '',

                    ]);
                }

            }

        }

        //update payments
        if($request->payment){


            foreach ($request->payment as $key => $payment) {

                payments::where('id', $key)->update(
                    [
                        'payment' => $payment['payment'],
                        'date' => $payment['date'],
                        'type'=>$payment['type'],
                        'invoice_id' => $statment->id,

                    ]

                );

            }
        }
        if ($statment->net_blance == 0) {
            $statment->update([
                'paid' => 1
            ]);
        } else {
            $statment->update([
                'paid' => 0
            ]);
        }

        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $real_name = $file->getClientOriginalName();
                $store_Name = time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move('statment/', $store_Name);
                $sf = statment_file::create(['statment_id' => $statment->id, 'store_name' => $store_Name, 'real_name' => $real_name]);

                $data[] = $store_Name;
            }

        }
        return redirect()->back()->with('updated', ' statment  updated successfully');
    }

    public function cilent_statment(Request $request)
    {

        return view('m_design.Client.mainPage.statments');
        //
    }

    public function Client_statment_data(Request $request, $flag = false)
    {
        $account_id = $request->user()->store->account_id;
        $statments = statment::query()->with(['file']);
        $statments = $statments->where('account_id', $account_id);
        if ($request->has('statment_from_date') && $request->get('statment_from_date') != '') {

            if ($request->has('statment_to_date') && $request->get('statment_to_date') != '') {

                $to = new \DateTime($request->get('statment_to_date'));

                $from = new \DateTime($request->get('statment_from_date'));

                $statments = $statments->whereBetween('statment_date', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('statment_from_date'));
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $statments = $statments->whereBetween('statment_date', [$from, $to]);
            }
        }
        if ($request->has('paid') && $request->get('paid') != '') {
            $statments = $statments->where('paid', $request->get('paid'));
        }
        $st = clone $statments;
        $statment_to_get_netBlance = clone $statments;
        $stToNetBlance = clone $statments;
        $stmt = $st->select(DB::raw('sum(cod) as codSumation , sum(total_amount) as isnaadInvoice'), 'statment.*')->get();
        $sumNetBlance = $stToNetBlance->get()->reduce(function ($netBlancec, $s) {
            if ($s->paid == 0) {
                try {
                    $net = str_replace(',', '', $s->net_blance);
                    return $net + $netBlancec;
                } catch (Exception $e) {
                    dd($s->net_blance, $netBlancec, $e);
                }


            } else {
                return $netBlancec + 0;
            }

        }, 0);

        if ($flag) {
            return $statments;
        } else {

            return Datatables::of($statments->get())
                ->with(['cod' => $stmt[0]->codSumation, 'isnaadInvoice' => $stmt[0]->isnaadInvoice, 'sumNetBlance' => $sumNetBlance])
                ->make(true);
        }

    }


    public function Client_statment_one(Request $request, $id)
    {
        $account_id = $request->user()->store->account_id;
        $statmet = statment::find($id);
        if ($statmet == null || $statmet->account_id != $account_id) {
            return redirect('Client-statment');
        }
        return view('m_design.Client.mainPage.showOne_client_statment', ['statemnt' => $statmet]);
    }


    public function delete_statmet($id)
    {
        statment::find($id)->delete();
        return redirect()->back();
    }

    public function export_Billing_file(Request $request)
    {
        $statments = $this->get_statment($request, true);
        $statments = $statments->get();
        $data = [];
        $i = 0;
        foreach ($statments as $statment) {
            $data[$i]['inv'] = $statment->inv;
            $data[$i]['description_from_date'] = $statment->description_from_date;
            $data[$i]['description_to_date'] = $statment->description_to_date;
            $data[$i]['account'] = $statment->acount->name;
            $data[$i]['invoice_date'] = $statment->initial_date;
            $data[$i]['last_date'] = $statment->last_date;
            if ($statment->paid) {
                $data[$i]['statment'] = 'paid';
            } else {
                $data[$i]['statment'] = 'Unpaid';
            }
            $data[$i]['total_amount'] = $statment->total_amount;
            $data[$i]['cod'] = $statment->cod;
            $data[$i]['balance'] = $statment->balance;
            $i++;
        }
        return Excel::download(new BillingExport($data), 'statment.xlsx');
    }

    public function export_Billing_file_client(Request $request)
    {
        $statments = $this->Client_statment_data($request, true);
        $statments = $statments->get();

        $data = [];
        $i = 0;
        foreach ($statments as $statment) {
            $data[$i]['inv'] = $statment->inv;
            $data[$i]['description_from_date'] = $statment->description_from_date;
            $data[$i]['description_to_date'] = $statment->description_to_date;
            $data[$i]['account'] = $statment->acount->name;
            $data[$i]['invoice_date'] = $statment->initial_date;
            $data[$i]['last_date'] = $statment->last_date;
            if ($statment->paid) {
                $data[$i]['statment'] = 'paid';
            } else {
                $data[$i]['statment'] = 'Unpaid';
            }
            $data[$i]['total_amount'] = $statment->total_amount;
            $data[$i]['cod'] = $statment->cod;
            $data[$i]['balance'] = $statment->balance;

            $i++;
        }


        return Excel::download(new BillingExport($data), 'statment.xlsx');
    }

    public function make_billing_paid(Request $request)
    {
        $statments = statment::whereIn('id', $request->ids)->get();
        foreach ($statments as $statment) {
            $statment->update(['paid' => '1', 'balance' => 0]);
        }
        return response()->json([
            'status' => true
        ]);
    }

    private function udateVaueBeforUpdate($value)
    {
        return str_replace(',', '', $value);
    }

    public function getNotPaidStatment($id){
        $statments= statment::where('account_id', $id)->where('paid',0)->with('payments')->get();
        return response()->json([
            'success'=>true,
            'statments'=>$statments
        ]);
    }
    public function delete_payment($id){

        $payment=    payments::find($id)->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
