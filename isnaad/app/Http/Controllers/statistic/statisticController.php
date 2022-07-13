<?php

namespace App\Http\Controllers\statistic;
use App\carrier;
use App\carrier_city;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class statisticController extends Controller
{
    public function carrierPerformance()
    {

        $carreires=carrier::all();
        $cities=carrier_city::all();
        $store=store::all();
       return view('test',['carreires'=>$carreires,'cities'=>$cities,'stores'=>$store]);
    }

    public function getCarrierPerformance(Request $request){

        $order=order::query();

        if ($request->has('store') && $request->get('store') != '') {

            $order = $order->whereHas('store', function ($query) use ($request) {
                $query->where('account_id','=',  $request->get('store') );
            });
        }
        if ($request->has('city') && $request->get('city') != '') {
            $order=  $order->where('city',$request->city);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $order = $order->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $order = $order->whereBetween('created_at', [$from, $to]);
            }
        }
        $order=$order->select(DB::raw('count(*) as count'),'carrier','order_status')->groupBy(['carrier','order_status'])->where('active','1')->get();
        $carrier=array_unique($order->pluck('carrier')->toArray());
        $date= $order->groupBy(['carrier']);
        $data= $date->map(function ($item){
            $ar=[];
            $countAll=0;
            foreach ($item as $i){

                $ar[$i->order_status]=$i->count;
                $countAll=$countAll+$i->count;
            }
            $ar['countAll']=$countAll;
            return $ar;
        });

        return response()->json([
            'per'=>$data,
             'carrier'=>$carrier
        ]);



    }


    public function getCarrierCodAndPaid(Request $request){

        $orderCod=order::query();
        $orderCod->where('order_status','Delivered');
        $orderCod->select('carrier',DB::raw('count(*) as count'))->where([['active',1],['cod_amount','>','0']]);
        if ($request->has('city') && $request->get('city') != '') {
            $orderCod=  $orderCod->where('city',$request->city);
        }

        if ($request->has('store') && $request->get('store') != '') {

            $orderCod = $orderCod->whereHas('store', function ($query) use ($request) {
                $query->where('account_id','=',  $request->get('store') );
            });

        }   if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orderCod = $orderCod->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orderCod = $orderCod->whereBetween('created_at', [$from, $to]);
            }
        }
        $orderCod= $orderCod->groupBy('carrier')->get();
        $carrier=$orderCod->pluck('carrier');

        $orderPaid=order::query();
        $orderPaid->where('order_status','Delivered');
        $orderPaid->select('carrier',DB::raw('count(*) as countPAId'))->where([['active',1],['cod_amount','=','0']]);
        if ($request->has('store') && $request->get('store') != '') {

            $orderPaid = $orderPaid->whereHas('store', function ($query) use ($request) {
                $query->where('account_id','=',  $request->get('store') );
            });

        }
        if ($request->has('city') && $request->get('city') != '') {
            $orderPaid=  $orderPaid->where('city',$request->city);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orderPaid = $orderPaid->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orderPaid = $orderPaid->whereBetween('created_at', [$from, $to]);
            }
        }

        $orderPaid= $orderPaid->groupBy('carrier')->get();

        $i=0;
        $array=array();

        foreach ($orderCod as $or){
           $Orpaid= $orderPaid->where('carrier',$or->carrier)->first();


            $cod=0;
            $paid=0;
              if(isset($or->count)){
                  $cod=$or->count;
              } if(isset($Orpaid->countPAId)){

                $paid=$Orpaid->countPAId;

              }

            $array[$or->carrier]=[
                'COD'=>$cod,
                'Paid'=>$paid,

            ];
              $i++;
        }
     return response()->json([
         'carrier'=>$carrier,
         'arrray'=>$array
     ]);

    }
}
