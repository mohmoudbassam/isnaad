<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\Exports\CancelExport;
use App\Exports\CarrierReportExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\order_cancel;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use App\Exports\OrderExportInoiceReport;


class cancelOrderController extends Controller
{
    public function index()
    {
        $sotres=store::all();
        return view('newDesign.Report.cancel',['sotres'=>$sotres]);
    }


    public function getCnacel(Request $request ,$flag=false){

        $cancel_order = order_cancel::query()->with('store');
            if($request->has('account_id') && $request->get('account_id') !=''){
                $cancel_order=  $cancel_order->where('account_id',$request->get('account_id'));
            }

        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('from') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));

                $from = new \DateTime($request->get('from'));

                $cancel_order = $cancel_order->whereBetween('cancel_date', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $cancel_order = $cancel_order->whereBetween('cancel_date', [$from, $to]);
            }
        }
        if($flag){
            return $cancel_order;
        }else{
            return Datatables::of($cancel_order->get())
                ->make(true);
        }


    }


    public function ExportExcel(Request $request){
        $order=  $this->getCnacel($request,true);
        $orders=$order->get();

        $data=[];
        $i=0;
        foreach ($orders as  $or){
            $data[$i]['order_number']=$or->order_number;
            $data[$i]['f_name']=$or->f_name;
            $data[$i]['city']=$or->city;
            $data[$i]['store']=$or->store->name;
            $data[$i]['cancel_date']=$or->cancel_date;
            $i++;
        }

        return Excel::download(new CancelExport($data), 'cancel_order.xlsx');
    }

}
