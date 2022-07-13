<?php


namespace App\Helpers;


use App\Classes\AramexAPI;
use App\Classes\Aymakan;
use App\Classes\BARQ;
use App\Classes\Mkhdoom;
use App\Classes\Forrun;
use App\Classes\Sama;
use App\Classes\Smsa;
use App\Classes\Tamex;
use App\Classes\Zajil;
use App\Classes\FDA;
use App\interrupted_orders;
use App\order;
use App\carrier_city;
use App\store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Classes\Mahmoul;
use App\Classes\Beez;
use App\store_carrier;
use Illuminate\Support\Facades\DB;
use App\carrier;

trait store_shipment
{


    public function dispatcher_shipment_in($order)
    {
        $mytime = Carbon::now();
     /*   $countForRun= order::where([['carrier','Forrun'],['created_at','>','2020-12-15'],['active',1]])->get()->count();
        $countdayForRun= order::where([['carrier','Forrun'],['created_at','>',Carbon::now()->format('yy-m-d')],['active',1]])->get()->count();
        if($countForRun !=260 && $countdayForRun!=55 && $order->AcountID != 33 && $order->CODamount == 0 && $order->AcountID != 22){
            return [
                'shipment' => Forrun::create_shipment($order),
                'carrier' => 'Forrun',
                'shiped_methode' => 'Forrun'
            ];
        }*/
        if ($order->AcountID == 7 && $order->WeightSum >= 50) {

            $shipment = AramexAPI::create_shipment($order);
            return [
                'shipment' => $shipment,
                'carrier' => 'Aramex',
                'shiped_methode' => 'EAMXDOM'
            ];

        }
        if ($order->AcountID == 33) {
            return [
                'shipment' => Mkhdoom::create_shipment($order),
                'carrier' => 'Mkhdoom',
                'shiped_methode' => 'Mkhdoom'
            ];

        }
        if ($order->AcountID == 5) {
            if ($order->CODamount > 0) {
                return [
                    'shipment' => Tamex::create_shipment($order),
                    'carrier' => 'Tamex',
                    'shiped_methode' => 'Tamex'
                ];
            } else {
                return [
                    'shipment' => BARQ::create_shipment($order),
                    'carrier' => 'BARQ',
                    'shiped_methode' => 'BARQ'
                ];
            }
        }

        //  $thisDay=  $mytime->toDateTimeString();
        $thisDay = Carbon::parse($mytime)->startOfDay();
        $thisDay = $thisDay->toDateTimeString();
        $orders = order::where([['store_id', $order->AcountID], ['created_at', '>=', $thisDay], ['city', 'Riyadh']])->get();
        $count = $orders->count();
        $count = $count % 200;
        $priorities = store_carrier::where([['account_id', $order->AcountID], ['place', 0]])->get();

        foreach ($priorities as $priority) {
            if ($this->in_range($priority->from, $priority->to, $count)) {
                if($priority->carrier_name=='FDA'){
                    $fdaCount= order::where([['carrier', 'FDA'], ['created_at', '>=', $thisDay], ['city', 'Riyadh']])->get()->count();
                    if($fdaCount >=60){
                        if($priority->priority+1>7){
                            $priority=   $priorities->where('priority',1);
                        }else{
                            $priority=   $priorities->where('priority',$priority->priority+1);
                        }

                    }
                }
                // dd($carrier);
                try {
                    $path = 'App\Classes\\';
                    $carrier = $priority->carrier_name;
                    if ($carrier == 'BARQ') {

                        if ($order->CODamount > 0) {

                            return [
                                'shipment' => Mkhdoom::create_shipment($order),
                                'carrier' => 'Mkhdoom',
                                'shiped_methode' => 'Mkhdoom'
                            ];
                        }
                    }
                    $class_called = $path . $carrier;
                    $shipment = $class_called::create_shipment($order);
                } catch (\Exception $e) {
                    Log::error('account_id ' . $order->AcountID . " form " . $priority->from . " to :" . $priority->to . "carrrier" . "$priority->carrier_name ");
                }

                if ($shipment['status'] == 'success') {
                    return [
                        'shipment' => $shipment,
                        'carrier' => $priority->carrier_shipEdig,
                        'shiped_methode' => $priority->shiped_methode
                    ];
                } else {
                    return [
                        'shipment' => [
                            'status' => 'false'
                        ]
                    ];
                }

            }
        }

    }


    private function in_range($from, $to, $count)
    {
        if ($count >= $from && $count <= $to) {
            return true;
        }
        return false;
    }

    private function check_city($city_id, $carrier_id)
    {

        $carrier_city = carrier_city::where([['carrier_id', $carrier_id], ['city_id', $city_id]])->first();

        if ($carrier_city) {

            return true;
        } else {

            return false;
        }
    }

    public function dispatcher_shipment_out($order, $city_id, $pr = 0, $period = 0)
    {

        //  $Tamexcity = self::check_city($city_id, 3);
        if ( $order->AcountID == 33 && $order->custCity == 'Jeddah') {

            $shipment = Smsa::create_shipment($order);
            return [
                'shipment' => $shipment,
                'carrier' => 'Smsa',
                'shiped_methode' => 'Smsa'
            ];

        }

        if ($order->AcountID == 7 && $order->WeightSum >= 50) {

            $shipment = AramexAPI::create_shipment($order);
            return [
                'shipment' => $shipment,
                'carrier' => 'Aramex',
                'shiped_methode' => 'EAMXDOM'
            ];

        }
        $mytime = Carbon::now();
        $thisDay = Carbon::parse($mytime)->startOfDay();
        $thisDay = $thisDay->toDateTimeString();
        $orders = order::where([['store_id', $order->AcountID], ['created_at', '>=', $thisDay], ['city', '!=', 'Riyadh'], ['country', '=', 'SA']])->get();
        $count = $orders->count();

        $pr = $pr % 8;
        $count = $count % 200;

        if ($pr == 0 && $period == 0) {
            // $priority = store_carrier::where([['account_id', $order->AcountID], ['place', 1]])
            //   ->whereBetween(DB::row($count), [DB::row('from'), DB::row('to')])
            // ->get();
            //  $priority=  DB::table('store_carriers')->where('account_id', $order->AcountID)->where('place', 1)->whereBetween(DB::raw($count), [DB::raw('from'), DB::raw('to')])->first();
            $priority = store_carrier::where([['account_id', $order->AcountID], ['place', 1]])
                ->get();

            $priority = $this->getPriorty($priority, $count);


            //dd($priority);
            //  if($order->AcountID==28){
            // dd( $priority);
            //   }

        } else {
            if ($pr == 0) {
                $pr = 1;
            }
            $priority = store_carrier::where([
                ['account_id', $order->AcountID],
                ['place', 1], ['priority', $pr]
            ])->first();


        }
        $carrier = $priority->carrier_name;
        if ($carrier == 'Smsa') {

            $shipment = Smsa::create_shipment($order);
            return [
                'shipment' => $shipment,
                'carrier' => 'Smsa',
                'shiped_methode' => 'Smsa'
            ];
        }

        if ($carrier == 'AramexAPI') {

            $shipment = AramexAPI::create_shipment($order);
            return [
                'shipment' => $shipment,
                'carrier' => 'Aramex',
                'shiped_methode' => 'EAMXDOM'
            ];
        }

        // Log::error('sotre shpment' . $order->MLVID.'count'.$count."account_id". $order->AcountID);
        /* if(isset($priority->carrier_name)){
  Log::error('sotre shpment' . $order->MLVID.'count'.$count."account_id". $order->AcountID);
         }else{
             Log::error('sotre shpment' . $order->MLVID.'count'.$count."account_id". $order->AcountID);
         }*/


        /* if ($carrier == 'AramexAPI' || $carrier == 'Smsa' || $carrier == 'Aymakan' || $carrier == 'Tamex') {

             if ($carrier == 'AramexAPI') {

                 $shipment = AramexAPI::create_shipment($order);

                 return [
                     'shipment' => $shipment,
                     'carrier' => 'Aramex',
                     'shiped_methode' => 'EAMXDOM'
                 ];
             } elseif ($carrier == 'Smsa') {

                 $shipment = Smsa::create_shipment($order);
                 return [
                     'shipment' => $shipment,
                     'carrier' => 'Smsa',
                     'shiped_methode' => 'Smsa'
                 ];
             } elseif ($carrier == 'Aymakan') {
                  if ($order->CODamount > 0) {
                      return $this->dispatcher_shipment_out($order, $city_id, $priority->priority + 1, $period + 1);
                  }
                 $shipment = Aymakan::create_shipment($order);
                 return [
                     'shipment' => $shipment,
                     'carrier' => 'Aymakan',
                     'shiped_methode' => 'Aymakan'
                 ];
             } elseif ($carrier == 'Tamex') {

                 $shipment = Tamex::create_shipment($order);
                 return [
                     'shipment' => $shipment,
                     'carrier' => 'Tamex',
                     'shiped_methode' => 'Tamex'
                 ];
                    return $this->dispatcher_shipment_out($order, $city_id, $priority->priority + 1, $period + 1);
             }
         }*/

        $carrier = carrier::where('name', $priority->carrier_shipEdig)->first();

        $check_city_flag = $this->check_city($city_id, $carrier->id);

        if ($check_city_flag) {
            $path = 'App\Classes\\';

            $carrier = $priority->carrier_name;

            $class_called = $path . $carrier;
            $shipment = $class_called::create_shipment($order);
            return [
                'shipment' => $shipment,
                'carrier' => $priority->carrier_shipEdig,
                'shiped_methode' => $priority->shiped_methode
            ];
        } else {

            return $this->dispatcher_shipment_out($order, $city_id, $priority->priority + 1, $period + 1);
        }


    }

    private function getPriorty($priorties, $count)
    {
        foreach ($priorties as $priorty) {
            if ($count >= $priorty->from && $count <= $priorty->to) {

                return $priorty;
            }
        }
    }
}




