<?php

namespace App\Classes;


use App\carrier;
use App\city;
use App\city_name;
use App\carrier_city;
use App\Barq_order_id;
use App\order;
use App\store;
use http\Env\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Redirect;
use PDF;
use App\Helpers\update_stores;
use Carbon\Carbon;

class BARQ
{
    static $username = 'aalomar@isnaad.sa';
    static $password = 'b81cba2d';
    static $base_url = 'https://live.barqfleet.com/api/v1/merchants/';

//    public static function authorization()
//    {
//        $data = [
//            'username' => self::$username,
//            'password' => self::$password
//        ];
//        try {
//            $result = self::send_request(json_encode($data), 'login', 'POST', 0);
//            if ($result->status == 'success') {
//                $id_token = $result->data->id_token;
//                $BARQ = carrier::where('name', 'BARQ')->first();
//                $BARQ->token = $id_token;
//                $BARQ->save();
//                return $id_token;
//            } else {
//                Log::error('error generating token for BARQ');
//                return 0;
//            }
//        } catch (\Exception $e) {
//            Log::error('error generating token for BARQ ' . $e->getMessage());
//            return 0;
//        }
//    }

    public static function send_request($data, $end_point, $request_type)
    {
        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json');
        //   if ($token == 1) {
        $BARQ = carrier::where('name', 'BARQ')->first();
        $header['Authorization'] = $BARQ->token;
        // }
        $client = new GuzzleHttpClient();
        $res = '';
        try {
            if ($request_type == 'POST') {
                // dd(159);
                $res = $client->$request_type(self::$base_url . $end_point, [
                    'headers' => $header,
                    'body' => $data
                ]);
            } else {
                //dd(555);
                //   if ($token == 1) {
                $BARQ = carrier::where('name', 'BARQ')->first();
                $header['Authorization'] = $BARQ->token;
                // }
                $res = $client->$request_type(self::$base_url . $end_point, [
                    'headers' => $header,
                ]);
            }
            dd(555);
            return json_decode($res->getBody()->getContents());

        } catch (\Exception $exception) {
            dd('error');
            Log::error('error in send request for BARQ ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        //  dd(21);
        //  dd($order->WeightSum);
        $BARQ = carrier::where('name', 'BARQ')->first();
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $payment_type = ($cod_amount > 0) ? 1 : 0;
        $data = [
            'payment_type' => $payment_type,
            'shipment_type' => 0,
            'hub_code' => $order->sender_name,
            'merchant_order_id' => $order->MLVID,
            'invoice_total' => $cod_amount,
            "customer_details" =>
                [
                    "first_name" => $order->custFName,
                    "last_name" => '_',
                    "country" => $order->custCountry,
                    "city" => $order->custCity,
                    "mobile" => $order->custPhone,
                    "address" => $order->custAddress1 . ' ' . $order->custAddress2
                ],
            "products" =>
                [
                    [
                        "sku" => $order->All_Sku,
                        "serial_no" => $order->All_Sku,
                        "name" => $order->description_total,
                        "color" => '',
                        "brand" => '',
                        "price" => $order->declared_total,
                        "weight_kg" => $order->WeightSum,
                        "qty" => '1',
                    ]
                ]
            ,
            "destination" =>
                [
                    "latitude" => '0.0',
                    "longitude" => '0.0'
                ]
        ];
        $data = json_encode($data);
        //dd($data);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://live.barqfleet.com/api/v1/merchants/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: eyJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxNDQ5LCJleHAiOjE3NjAzNzEwNTl9.8TwTH92_-Js_Yf5qtl6HqO6MgymVRxVna-wK6wubMXQ",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);

        // $result = self::send_request($data, 'orders', 'POST');
        //dd($result);
        if ($result) {
            if (isset($result->id)) {
                $barq = new Barq_order_id;
                $barq->ship_no = $order->MLVID;
                $barq->barq_id = $result->id;
                $barq->save();
                $data = [
                    'tracking_number' => $result->tracking_no,
                    'waybill_url' => self::create_label($result->id),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from BARQ " . json_encode($result),
                    'status' => 'error'
                ];
                Log::alert("Error from BARQ : " . $order->MLVID . ' ' . json_encode($result));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to BARQ",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To BARQ: " . $order->MLVID);
            return $data;
        }
    }

    public static function create_label($id)
    {
        //$result = self::send_request('', 'orders/airwaybill/'.$id, 'GET');


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://live.barqfleet.com/api/v1/merchants/orders/airwaybill/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: eyJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxNDQ5LCJleHAiOjE3NjAzNzEwNTl9.8TwTH92_-Js_Yf5qtl6HqO6MgymVRxVna-wK6wubMXQ"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //  dd($response) ;

        $filename = 'Barq-' . $id . '.pdf';
        file_put_contents(getcwd() . '/Barq_labels' . "/" . $filename, $response);
        $file = Url('/') . '/Barq_labels' . "/" . $filename;

        return $file;
    }

    public static function update_status($ship_num, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://live.barqfleet.com/api/v1/merchants/orders/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: eyJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxNDQ5LCJleHAiOjE3NjAzNzEwNTl9.8TwTH92_-Js_Yf5qtl6HqO6MgymVRxVna-wK6wubMXQ",
                "Content-Type: application/json",
                "Language: ar"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);
        if ($ship_num == 37832) {
//dd($result);
        }

        if (isset($result)) {

            if ($result->order_status == 'completed') {
                if (isset($result->shipment)) {
                    if ($result->shipment->is_completed == true) {

                        $order = order::where([['shipping_number', $ship_num], ['active', '1']])->first();

                        $date = $result->updated_at;
                        $timestamp = strtotime($date);
                        $final_date = date("Y-m-d", $timestamp);
                        if ($order->delivery_date != $final_date){
                            $order->delivery_date = $final_date;
                            $order->save();
                            Log::alert("Delivered BARQ updated: " . $ship_num);
                        }


                        // $order->order_status = 'Delivered';
                        // $order->Last_Status = $result->order_status;
                        // $date = $result->updated_at;
                        //$timestamp = strtotime($date);
                        // $final_date = date("Y-m-d", $timestamp);
                        //$order->delivery_date = self::checkDate($final_date,$order->store_id);
                        // $order->save();
                        //Log::alert("Delivered BARQ: " . $ship_num);
                    }

                }
            }
            /*
            elseif ($result->order_status == 'cancelled' || $result->order_status == 'returned') {
                //  $order = order::where([['shipping_number', $ship_num], ['active', '1']])->first();
                //  $order->order_status = 'Returned';
                // $order->Last_Status = $result->order_status;
                //$order->save();
                Log::alert("Returned BARQ: " . $ship_num);
            } else {
                $order = order::where([['shipping_number', $ship_num], ['active', '1']])->first();
                $order->order_status = 'inTransit';
                $order->Last_Status = $result->order_status;
                $order->save();
                Log::alert("inTransit BARQ: " . $ship_num);
            }
            */
        } else {
            Log::alert("Error in update status BARQ " . $ship_num);
        }
    }

    private static function checkDate($date,$acount_id)
    {
        $firstDay = Carbon::now()->firstOfMonth();
        $middlMonth = $firstDay->addDay(15)->toDateString();
        $currentDate = Carbon::now()->format('Y-m-d');
        if($acount_id ==29 ||$acount_id==13){
            if ($currentDate >= $middlMonth) {
                if($date < $middlMonth){
                    return $middlMonth;
                }else{
                    return $date;
                }
            }else{
                return $date;
            }

        }
        $OrderMonth=Carbon::parse($date)->month;
        if($OrderMonth==Carbon::now()->month){
            return $date;
        }

        if($OrderMonth!= Carbon::now()->month){
            $startDate = Carbon::now();
            $firstDay = $startDate->firstOfMonth();
            return $firstDay->toDateString();
        }

    }
}
