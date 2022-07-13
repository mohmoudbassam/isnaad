<?php

namespace App\Classes;

use App\carrier;
use App\carrier_city;
use App\city;
use App\order;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\update_stores;
use App\Models\zajil_request;

class Zajil {

    static $test_base_url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/';
    static $prod_base_url = 'http://app.shipsy.in/api/customer/integration/consignment/';
    static $Test_API_KEY = '060c5b0c0d741108539d55ca61b1c5';
    static $Prod_API_KEY = '960012ad8b2e1998475170876f70c4';
    static $CustomerCode = 'ISNAAD 01';

    public static function send_request($data, $end_point, $request_type)
    {
        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json', 'API-KEY' => '960012ad8b2e1998475170876f70c4');

        $client = new GuzzleHttpClient();
        $res = '';
        try {
            if ($request_type == 'POST') {
                $res = $client->$request_type(self::$prod_base_url . $end_point, [
                    'headers' => $header,
                    'body' => $data
                ]);
                return json_decode($res->getBody()->getContents());
            } else {
                $res = $client->$request_type(self::$prod_base_url . $end_point, [
                    'headers' => ['Content-Type' => 'application/json'
                        , 'api-key' => self::$Prod_API_KEY],
                    //'body' => $data
                ]);
                return json_decode($res->getBody()->getContents());
            }
        } catch (\Exception $exception) {
            Log::error('error in send request for Zajil ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $paymode= ($cod_amount == 0 ) ? '' : 'Cash';
        $data = [
            'consignments' => [
                [
                    'customer_code' => self::$CustomerCode,
                    'reference_number' => '',
                    'service_type_id' => 'B2C',
                    'load_type' => 'NON-DOCUMENT',
                    'description' => $order->description_total,
                    'cod_favor_of' => '',
                    'dimension_unit' => 'cm',
                    'length' => $order->Length,
                    'width' => $order->Width,
                    'height' => $order->Height,
                    'weight_unit' => 'kg',
                    'weight' => $order->WeightSum,
                    'declared_value' => $order->declared_total,
                    'declared_price' => '',
                    'cod_amount' => $cod_amount,
                    'cod_collection_mode' => $paymode,
                    'prepaid_amount' => '',
                    'num_pieces' => '1',
                    'customer_reference_number' => '',
                    'is_risk_surcharge_applicable' => true,
                    'origin_details' => [
                        "name" => $order->sender_name,
                        'phone' => '966537737764',
                        'alternate_phone' => '',
                        'address_line_1' => 'Al Mishael sulay',
                        'address_line_2' => 'Istanbul St.',
                        'city' => 'riyadh',
                        'state' => 'riyadh'
                    ],
                    'destination_details' => [
                        'name' => $order->custFName . ' ' . $order->custLName,
                        'phone' => $order->custPhone,
                        'alternate_phone' => '',
                        'address_line_1' => $order->custAddress1,
                        'address_line_2' => $order->custAddress2,
                        'city' => $order->custCity,
                        'state' => $order->custState
                    ],
                    'pieces_detail' => [
                        'description' => $order->description_total,
                        'declared_value' => $order->declared_total,
                        'weight' => $order->WeightSum,
                        'height' => $order->Height,
                        'length' => $order->Length,
                        'width' => $order->Width
                    ],
                ]
            ]
        ];
        $data = json_encode($data);
        $result = self::send_request($data, 'softdata', 'POST');
        // dd($order->MLVID);
        if ($result) {
           // dd($order->MLVID);
             //dd($result);
            if (isset($result->status)) {
                if ($result->status == 'OK' && isset($result->data[0]->reference_number)) {
                    $data = [
                        'tracking_number' => $result->data[0]->reference_number,
                        'waybill_url' => self::create_label($result->data[0]->reference_number),
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];
                    return $data;
                } else {
                    $data = [
                        'msg' => "Error from Zajil " . json_encode($result->data),
                        'status' => 'error'
                    ];
                    return $data;
                }
            }else{
                $data = [
                    'msg' => "Error adding order to Zajil",
                    'status' => 'error'
                ];
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Zajil",
                'status' => 'error'
            ];
            return $data;
        }
    }

    public static function create_label($referance_number)
    {
        $data = [
            'referance_number' => $referance_number,
        ];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://app.shipsy.in/api/customer/integration/consignment/shippinglabel/link?reference_number=".$referance_number."&is_small=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "api-key: 960012ad8b2e1998475170876f70c4",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        //dd($response);
        //  $url=  substr($response->data->url, 0,-13 );

        // dd($url);
        if(isset($response->data->url)){
            return $response->data->url;
        }else{
            Log::error('AWB URL ISSUE');
        }

    }
    public static function update_status($tracking_number,$id){
        //$update_stores = new update_stores();
        $order=order::find($id);
        $result = self::send_request([], 'track?reference_number='.$tracking_number, 'GET');
          zajil_request::create([
            'order_id'=>$id
        ]);
      //  dd($result,$tracking_number);
        if(isset($result->status)){
            if($result->status=='delivered'){
                $order->order_status='Delivered';
                $date=$result->events[0]->event_time;
                
               // dd(date("Y-m-d", strtotime($result->events[0]->event_time)));
                $final_date= date("Y-m-d", substr($date, 0, 10));
            //    dd( $final_date);
                $order->delivery_date=$final_date;
                $order->Last_Status= $result->status;
                $order->save();
                 Log::error('delvierd in zajil '. $order->tracking_number);
                //  $store_id= $order->store_id;
                //  $update_stores->update_stores($order->order_number,$store_id,$order->order_status);
            }elseif($result->status=='rto'){
                $order->order_status='Returned';
                $order->Last_Status= $result->status;
                $order->save();
                //    $store_id= $order->store_id;
                //  $update_stores->update_stores($order->order_number,$store_id,$order->order_status);
            }else{
              $rto=false;
                foreach ($result->events as $event){
                    if($event->type=='rto'||$event->type=='RTO'){
                        $rto=true;
                    }
                }
                if($rto){
                    $order->order_status='Returned';
                    $order->Last_Status= $result->status;
                    $order->save();
                }else{
                    $order->order_status='inTransit';
                    $order->Last_Status= $result->status;
                    $order->save();
                }

            }
        }else{
            Log::error('error in order '. $order->tracking_number);
        }
    }

     private static function checkDate($date)
    {
    $OrderMonth=Carbon::parse($date)->month;
        if($OrderMonth!= Carbon::now()->month){
            return Carbon::now()->format('yy-m-d');
        }else{
              $startDate = Carbon::now();
    $firstDay = $startDate->firstOfMonth();
    return $firstDay->toDateString();
        }

    }
}
