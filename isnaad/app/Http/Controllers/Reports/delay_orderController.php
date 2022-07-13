<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\Exports\CarrierReportExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;

use App\Exports\DelayOrder;


class delay_orderController extends Controller
{
    public function index()
    {
        $carreires = carrier::all();
        $store = store::all();
        return view('m_design.delayOrders', ['carriers' => $carreires,'store'=>$store]);
    }

    public function get_delay(Request $request,$flag=false)
    {
        $dt = Carbon::now();
        $thisDay=$dt->toDateTimeString();
        $thisDay=$dt->format('Y-m-d');

        if($request->has('place')&& $request->get('place')=='ryad'){

            $days=1;
            $orders = order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id','city'
            ])->whereRaw(
                'DATEDIFF('."'$thisDay'".',shipping_date)>='.$days

            )->whereNotNull('shipping_date')
                ->where([['order_status','inTransit'],['city','Riyadh'],['active',1]])

                ->with(['store','carriers']);
            if ($request->has('carrier') && $request->get('carrier') != '') {


                $orders = $orders->where('carrier', $request->carrier);


            }

        }elseif ($request->has('place')&& $request->get('place')=='outryad'){
            $days=4;
            $orders = order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id'
                ,'city'
            ])->whereRaw(
                'DATEDIFF('."'$thisDay'".',shipping_date)>'.$days

            )->whereNotNull('shipping_date')
                ->where([['order_status','inTransit'],['city','!=','Riyadh'],['active',1]])

                ->with(['store','carriers']);
            if ($request->has('carrier') && $request->get('carrier') != '') {


                $orders = $orders->where('carrier', $request->carrier);


            }

        }elseif($request->has('place')&& $request->get('place')=='outsa'){
            $days=7;
            $orders = order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id',
                'city'
            ])->whereRaw(
                'DATEDIFF('."'$thisDay'".',shipping_date)>'.$days

            )->whereNotNull('shipping_date')
                ->where([['order_status','inTransit'],['country','!=','SA'],['active',1]])

                ->with(['store','carriers']);
            if ($request->has('carrier') && $request->get('carrier') != '') {


                $orders = $orders->where('carrier', $request->carrier);


            }

        }
        if($request->has('place')&& $request->get('place')=='all'){

            $orders= order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id',
                'city'
            ])->where([['active',1],['order_status','inTransit']])->with(['store','carriers'])->get()->filter(function ($or){
                if($or->city=='Riyadh'){
                    if($this->getNowdate()->diffInDays($or->shipping_date)>1){
                        return $or;
                    }
                    }elseif($or->country=='SA'){

                    if($this->getNowdate()->diffInDays($or->shipping_date)>7){
                        return $or;
                    }

                }elseif($or->city!='Riyadh'){
                    if($this->getNowdate()->diffInDays($or->shipping_date)>2){
                        return $or;
                    }
                }
            });
        }



        if($flag){
            return $orders;
        }else{
            return Datatables::of($orders)
                ->addColumn('days', function ($order) {
                    return  $this->getNowdate()->diffInDays($order->shipping_date);
                })->
                rawColumns(['days'])
                ->make(true);
        }



    }

    public function export_delay_order(Request $request){

        $order=$this->get_delay($request,true);
        if($request->has('place')&& $request->get('place')=='all'){

        }else{
            $order=$order->get();
        }
        $data=[];
        $i=0;
        foreach ($order as  $or){
            $data[$i]['shipping_number']=$or->shipping_number;
            $data[$i]['order_number']=$or->order_number;
            $data[$i]['carrier']=$or->carrier;
            $data[$i]['shipping_date']=$or->shipping_date;
            $data[$i]['city']=$or->city;
            $data[$i]['days']=$this->getNowdate()->diffInDays($or->shipping_date);
            $data[$i]['tracking_number']=$or->tracking_number;
            $i++;


        }

        return Excel::download(new DelayOrder($data), 'delay-order.xlsx');
    }
    public function getNowdate(){
        $dt = Carbon::now();
        return $dt;
    }



}
