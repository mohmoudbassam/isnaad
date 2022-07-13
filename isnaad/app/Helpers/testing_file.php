
<?php

/*
namespace App\Helpers;


use App\Classes\AramexAPI;
use App\Classes\Aymakan;
use App\Classes\Mkhdoom;
use App\Classes\Forrun;
use App\Classes\Sama;
use App\Classes\Smsa;
use App\Classes\Tamex;
use App\Classes\Zajil;
use App\interrupted_orders;
use App\order;
use App\carrier_city;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait store_shipment
{

    public function Sorrah_in($order)
    {
        $mytime = Carbon::now();
        //  $thisDay=  $mytime->toDateTimeString();
        $thisDay = Carbon::parse($mytime)->startOfDay();
        $thisDay = $thisDay->toDateTimeString();
        $orders = order::where([['store_id', '13'], ['created_at', '>=', $thisDay], ['city', 'Riyadh']])->get();
        if ($orders->count() <= 30) {
            //  dd('Sorrah_in <30');
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';

        } elseif ($orders->count() > 30 && $orders->count() <= 50) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';

        } elseif ($orders->count() > 50 && $orders->count() <= 70) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 70 && $orders->count() <= 90) {
            $shipment = Tamex::create_shipment($order);
            $carrier = 'Tamex';
            $shiped_methode = 'Tamex';
        } elseif ($orders->count() > 90 && $orders->count() <= 120) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 120 && $orders->count() <= 140) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 140 && $orders->count() <= 160) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 160 && $orders->count() <= 180) {
            $shipment = Tamex::create_shipment($order);
            $carrier = 'Tamex';
            $shiped_methode = 'Tamex';
        } elseif ($orders->count() > 180 && $orders->count() <= 200) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        }
        return [
            'shipment' => $shipment,
            'carrier' => $carrier,
            'shiped_methode' => $shiped_methode
        ];
    }

    public function Sorrah_out($order, $city_id)
    {
        $mytime = Carbon::now();
        //  $thisDay=  $mytime->toDateTimeString();
        $thisDay = Carbon::parse($mytime)->startOfDay();
        $thisDay = $thisDay->toDateTimeString();

        $orders = order::where([['store_id', '13'], ['created_at', '>=', $thisDay]])->get();
        if ($orders->count() <= 30) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address from Zajil';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }

        } elseif ($orders->count() > 30 && $orders->count() <= 50) {
            $carrier_city = carrier_city::where([['carrier_id', '4'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Aymakan::create_shipment($order);
                $carrier = 'Aymakan';
                $shiped_methode = 'Aymakan';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address from aymakan';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }

        } elseif ($orders->count() > 50 && $orders->count() <= 70) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address from Zajil';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($orders->count() > 70 && $orders->count() <= 90) {
            $carrier_city = carrier_city::where([['carrier_id', '3'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Tamex::create_shipment($order);
                $carrier = 'Tamex';
                $shiped_methode = 'Tamex';

            } else {
                //AramexAPi
                //   $carrier_city = carrier_cities::where([['carrier_id','5'],['city_id',$city_id]])->first();

                //   $city_name=   $carrier_city->name;
                //     $order->custCity=$city_name;
                $shipment = AramexAPI::create_shipment($order);
                if ($shipment['status'] == 'success') {
                    $carrier = 'Aramex';
                    $shiped_methode = 'EAMXEPE';
                } else {
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = 'Sorrah';
                    $interrubted_orders->issue = 'Address';
                    $interrubted_orders->save();
                    Log::error('City name is incorrect for order ' . $order->MLVID);
                    return [
                        'shipment' => [
                            'status' => 'false'
                        ]
                    ];
                }
            }
        } elseif ($orders->count() > 90 && $orders->count() <= 120) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($orders->count() > 120 && $orders->count() <= 140) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($orders->count() > 140 && $orders->count() <= 160) {
            $shipment = AramexAPI::create_shipment($order);
            if ($shipment['status'] == 'success') {
                $carrier = 'Aramex';
                $shiped_methode = 'EAMXEPE';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($orders->count() > 160 && $orders->count() <= 180) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($orders->count() > 180 && $orders->count() <= 200) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Sorrah';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        }
        return [
            'shipment' => $shipment,
            'carrier' => $carrier,
            'shiped_methode' => $shiped_methode
        ];
    }

    public function Robail_in($order)
    {
        $mytime = Carbon::now();
        $thisDay = Carbon::parse($mytime)->startOfDay();
        $thisDay = $thisDay->toDateTimeString();
        $orders = order::where([['store_id', '11'], ['created_at', '>=', $thisDay], ['city', 'Riyadh']])->get();
        if ($orders->count() <= 30) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 30 && $orders->count() <= 50) {
            $shipment = Tamex::create_shipment($order);
            $carrier = 'Tamex';
            $shiped_methode = 'Tamex';
        } elseif ($orders->count() > 50 && $orders->count() <= 70) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 70 && $orders->count() <= 90) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 90 && $orders->count() <= 120) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 120 && $orders->count() <= 140) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 140 && $orders->count() <= 160) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        } elseif ($orders->count() > 160 && $orders->count() <= 180) {
            $shipment = Tamex::create_shipment($order);
            $carrier = 'Tamex';
            $shiped_methode = 'Tamex';
        } elseif ($orders->count() > 180 && $orders->count() <= 200) {
            $shipment = Mkhdoom::create_shipment($order);
            $carrier = 'Mkhdoom';
            $shiped_methode = 'Mkhdoom';
        }
        return [
            'shipment' => $shipment,
            'carrier' => $carrier,
            'shiped_methode' => $shiped_methode
        ];
    }

    public function Robail_out($order, $city_id)
    {
       // dd($order);
        $mytime = Carbon::now();
        //  $thisDay=  $mytime->toDateTimeString();
        $thisDay = Carbon::parse($mytime)->startOfDay();
   
        $thisDay = $thisDay->toDateTimeString();
        //dd( $thisDay );
        $orders = order::where([['store_id', '11'], ['created_at', '>=', $thisDay], ['city', '!=', 'Riyadh'], ['country', 'SA']])->get();
        //dd($orders->count());
        $count=$orders->count()%200;
        if ($count <= 30) {
          //  dd($city_id);
            
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
              //  dd($order->custCity);
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
                //dd($shipment);
               // dd($shipment);
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Robail';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($count > 30 && $count <= 50) {
            //$carrier_city = carrier_city::where([['carrier_id','5'],['city_id',$city_id]])->first();
            //  if($carrier_city){
            // $city_name=   $carrier_city->name;
            // $order->custCity=$city_name;
            $shipment = Smsa::create_shipment($order);
            $carrier = 'Smsa';
            $shiped_methode = 'Smsa';

         //   dd('smsaaaa', $shipment);
            //}else{
            //// $interrubted_orders = new interrupted_orders();
            ////  $interrubted_orders->shipping_number = $order->MLVID;
            //  $interrubted_orders->order_number = $order->orderNum;
            ////  $interrubted_orders->carrier = $order->shipping_carrier;
            //$interrubted_orders->store = 'Robail';
            //$interrubted_orders->issue = 'Address';
            //$interrubted_orders->save();
            //Log::error('City name is incorrect for order ' . $order->MLVID);
            //    return [
            //                'shipment'=>[
            //                  'status'=> 'false'
            //            ]
            //      ];
            // }
        } elseif ($count > 50 && $count <= 70) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Robail';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($count > 70 && $count <= 90) {
            $carrier_city = carrier_city::where([['carrier_id', '3'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Tamex::create_shipment($order);
                $carrier = 'Tamex';
                $shiped_methode = 'Tamex';

            } else {
                //AramexAPi
                //   $carrier_city = carrier_cities::where([['carrier_id','5'],['city_id',$city_id]])->first();

                //   $city_name=   $carrier_city->name;
                //     $order->custCity=$city_name;
                $shipment = AramexAPI::create_shipment($order);
                if ($shipment['status'] == 'success') {
                    $carrier = 'Aramex';
                    $shiped_methode = 'EAMXEPE';
                } else {
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = 'Robail';
                    $interrubted_orders->issue = 'Address';
                    $interrubted_orders->save();
                    Log::error('City name is incorrect for order ' . $order->MLVID);
                    return [
                        'shipment' => [
                            'status' => 'false'
                        ]
                    ];
                }
            }
        } elseif ($count > 90 && $count <= 120) {
            //  $carrier_city = carrier_city::where([['carrier_id','5'],['city_id',$city_id]])->first();
            //  if($carrier_city){
            //     $city_name=   $carrier_city->name;
            //        $order->custCity=$city_name;
            $shipment = Smsa::create_shipment($order);
            $carrier = 'Smsa';
            $shiped_methode = 'Smsa';
            //  }else{
            //    $interrubted_orders = new interrupted_orders();
            //  $interrubted_orders->shipping_number = $order->MLVID;
            // $interrubted_orders->order_number = $order->orderNum;
            // $interrubted_orders->carrier = $order->shipping_carrier;
            // $interrubted_orders->store = 'Robail';
            // $interrubted_orders->issue = 'Address';
            // $interrubted_orders->save();
            // Log::error('City name is incorrect for order ' . $order->MLVID);
            //    return [
            //                 'shipment'=>[
            //                   'status'=> 'false'
            //             ]
            //       ];
            //}
        } elseif ($count > 120 && $count <= 140) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Robail';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($count > 140 && $count <= 160) {
            $shipment = AramexAPI::create_shipment($order);
            if ($shipment['status'] == 'success') {
                $carrier = 'Aramex';
                $shiped_methode = 'EAMXEPE';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Robail';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($count > 160 && $count <= 180) {
            $carrier_city = carrier_city::where([['carrier_id', '5'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Zajil::create_shipment($order);
                $carrier = 'Zajil';
                $shiped_methode = 'Zajil';
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = 'Robail';
                $interrubted_orders->issue = 'Address';
                $interrubted_orders->save();
                Log::error('City name is incorrect for order ' . $order->MLVID);
                return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
            }
        } elseif ($count > 180 && $count<= 200) {
            //         $carrier_city = carrier_city::where([['carrier_id','5'],['city_id',$city_id]])->first();
            //       if($carrier_city){
            //           $city_name=   $carrier_city->name;
            //         $order->custCity=$city_name;
            $shipment = Smsa::create_shipment($order);
            $carrier = 'Smsa';
            $shiped_methode = 'Smsa';
            //    }else{
            //      $interrubted_orders = new interrupted_orders();
            //    $interrubted_orders->shipping_number = $order->MLVID;
            //    $interrubted_orders->order_number = $order->orderNum;
            //   $interrubted_orders->carrier = $order->shipping_carrier;
            //  $interrubted_orders->store = 'Robail';
            // $interrubted_orders->issue = 'Address';
            // $interrubted_orders->save();
            // Log::error('City name is incorrect for order ' . $order->MLVID);
            //       return [
            //                 'shipment'=>[
            //                   'status'=> 'false'
            //             ]
            //       ];
            //}
            //}
            //dd($order->MLVID);

        }
     // dd(123);
     
            return [
                'shipment' => $shipment,
                'carrier' => $carrier,
                'shiped_methode' => $shiped_methode
            ];
    }
        public
        function Folicello_in($order)
        {
            $mytime = Carbon::now();
            //  $thisDay=  $mytime->toDateTimeString();
            $thisDay = Carbon::parse($mytime)->startOfDay();
            $thisDay = $thisDay->toDateTimeString();
            $orders = order::where([['store_id', '15'], ['created_at', '>=', $thisDay], ['city', 'Riyadh']])->get();
            $count = $orders->count();
            $division = $count / 20;
            $round = ceil($division);
            $mod = $round % 2;
            if ($mod == 0) {
                $shipment = Mkhdoom::create_shipment($order);
                $carrier = 'Mkhdoom';
                $shiped_methode = 'Mkhdoom';
            } else {
                $shipment = Mkhdoom::create_shipment($order);
                $carrier = 'Mkhdoom';
                $shiped_methode = 'Mkhdoom';
            }
            return [
                'shipment' => $shipment,
                'carrier' => $carrier,
                'shiped_methode' => $shiped_methode
            ];
        }

        public
        function Folicello_out($order, $city_id)
        {
            $carrier_city = carrier_city::where([['carrier_id', '4'], ['city_id', $city_id]])->first();
            if ($carrier_city) {
                $city_name = $carrier_city->name;
                $order->custCity = $city_name;
                $shipment = Aymakan::create_shipment($order);
                $carrier = 'Aymakan';
                $shiped_methode = 'Aymakan';
            } else {
                $shipment = Smsa::create_shipment($order);
                $carrier = 'Smsa';
                $shiped_methode = 'Smsa';
                if ($shipment['status'] == false) {
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = 'Folicello';
                    $interrubted_orders->issue = 'Address Smsa';
                    $interrubted_orders->save();
                    Log::error('City name is incorrect for order ' . $order->MLVID);
                     return [
                    'shipment' => [
                        'status' => 'false'
                    ]
                ];
                }
            }
            return [
                'shipment' => $shipment,
                'carrier' => $carrier,
                'shiped_methode' => $shiped_methode
            ];
        }
    }

    */


