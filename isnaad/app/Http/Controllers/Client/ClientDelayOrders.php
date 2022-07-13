<?php

namespace App\Http\Controllers\Client;

use App\carrier;
use App\Http\Controllers\Controller;
use App\Models\order_cancel;
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
class ClientDelayOrders extends Controller
{
    public function index()
    {
  $carreires = carrier::all();
        $store = store::all();

       return view('newDesign.Client.mainPage.delayOrder', ['carreires' => $carreires,'store'=>$store]);
    }

    public function get_delay(Request $request,$flag=false)
    {

        $dt = Carbon::now();
        $thisDay=$dt->toDateTimeString();
        $thisDay=$dt->format('Y-m-d');

        if($request->has('place')&& $request->get('place')=='ryad'){

            $days=2;
            $orders = order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id','city'
            ])->whereRaw(
                'DATEDIFF('."'$thisDay'".',shipping_date)>'.$days

            )->whereNotNull('shipping_date')
                ->where([['order_status','inTransit'],['city','Riyadh'],['active',1],['store_id',auth()->user()->store->account_id]])

                ->with(['store','carriers']);
            if ($request->has('carrier') && $request->get('carrier') != '') {


                $orders = $orders->where('carrier', $request->carrier);


            }
            $orders=$orders->get();

        }elseif ($request->has('place')&& $request->get('place')=='outryad'){
            $days=4;
            $orders = order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id'
                ,'city'
            ])->whereRaw(
                'DATEDIFF('."'$thisDay'".',shipping_date)>'.$days

            )->whereNotNull('shipping_date')
                ->where([['order_status','inTransit'],['city','!=','Riyadh'],['active',1],['store_id',auth()->user()->store->account_id]])

                ->with(['store','carriers']);
            if ($request->has('carrier') && $request->get('carrier') != '') {


                $orders = $orders->where('carrier', $request->carrier);


            }
            $orders=$orders->get();
        }elseif($request->has('place')&& $request->get('place')=='outsa'){
            $days=10;
            $orders = order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id',
                'city'
            ])->whereRaw(
                'DATEDIFF('."'$thisDay'".',shipping_date)>'.$days

            )->whereNotNull('shipping_date')
                ->where([['order_status','inTransit'],['country','!=','SA'],['active',1],['store_id',auth()->user()->store->account_id]])

                ->with(['store','carriers']);
            if ($request->has('carrier') && $request->get('carrier') != '') {


                $orders = $orders->where('carrier', $request->carrier);


            }
            $orders=$orders->get();
        }
        if($request->has('place')&& $request->get('place')=='all'){

            $orders= order::select([
                'id','shipping_number','order_number','shipping_date','tracking_number','carrier','store_id',
                'city'
            ])->where([['active',1],['order_status','inTransit'],['store_id',auth()->user()->store->account_id]])->with(['store','carriers'])->get()->filter(function ($or){
                if($or->city=='Riyadh'){
                    if($this->getNowdate()->diffInDays($or->shipping_date)>2){
                        return $or;
                    }
                }elseif($or->country=='SA'){

                    if($this->getNowdate()->diffInDays($or->shipping_date)>10){
                        return $or;
                    }

                }elseif($or->city!='Riyadh'){
                    if($this->getNowdate()->diffInDays($or->shipping_date)>4){
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
    public function getNowdate(){
        $dt = Carbon::now();
        return $dt;
    }
}
