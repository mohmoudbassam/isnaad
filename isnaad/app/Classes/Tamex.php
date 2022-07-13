<?php

namespace App\Classes;

use App\carrier;
use App\carrier_city;
use App\city;
use App\order;
use Illuminate\Http\File;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class Tamex{

    static $test_base_url = 'http://test.til.sa/api/v2/';
    static $prod_base_url = 'https://til.sa/api/v2/';
    static $Test_API_KEY= '45615da2b783af18097c531e6d72a07a';
    static $Prod_API_KEY= 'TMX247563ARTYU78XC87QW1236T39487';


    public static function send_request($data, $end_point, $request_type)
    {
        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json');

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
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => $data
                ]);
                return $res->getBody()->getContents();
            }
        } catch (\Exception $exception) {
            Log::error('error in send request for Tamex ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
//dd($order);
        $tamex = carrier::where('name', 'tamex')->first();
        $city = city::where('name', $order->custCity)->where('country_id', 1)->first();
        $carrier_city = carrier_city::where('city_id', $city->id)->where('carrier_id', $tamex->id)->first();
        if (!$carrier_city) {
            Log::error( 'city ID not found Tamex for order');
                  $data = [
                    'msg' => "city ID not found Tamex for order " ,
                    'status' => 'error'
                ];
                return $data;
        }
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $data = [
            'apikey' => self::$Prod_API_KEY,
            'pack_type' => '1',
            'pack_awb' => '',
            'pack_vendor_id' => $order->sender_name,
            'pack_reciver_name' => $order->custFName,
            'pack_reciver_phone' => $order->custPhone,
            'pack_reciver_country' => $order->custCountry,
            'pack_reciver_city' => $order->custCity,
            'pack_reciver_dist' => $order->custAddress2,
            'pack_desc' => $order->description_total.'_'.$order->MLVID,
            'pack_num_pcs' => $order->Qty_Item,
            'pack_weight' => $order->WeightSum,
            'pack_cod_amount' => $cod_amount,
            'pack_currency_code' => 'SAR',
            'pack_extra_note' => $order->CustComments,
            'pack_live_time' => '4',
            'pack_sender_name' => $order->sender_name,
            'pack_sender_phone' => '966537737764',
            'pack_sender_email' => $order->from_mail,
            'pack_send_country' =>'sa',
            'pack_send_city' => 'riyadh',
            'pack_sender_dist' => 'Al Mishael sulay',
            'pack_sender_street' => 'Istanbul St.',
            'pack_sender_zipcode' => '11491',
            'pack_reciver_email' => $order->sender_email,
            'pack_reciver_street' => $order->custAddress1,
            'pack_reciver_zipcode' => $order->custZip,
            'pack_reciver_building' => '',
            'pack_dimention' => '' . '15' . ':' . '15' . ':' . '15',
        ];
        $data = json_encode($data);
        $result = self::send_request($data, 'create', 'POST');

         // dd($result);
        if ($result) {
            if ($result->code == 0) {
                $data = [
                    'tracking_number' => $result->tmxAWB,
                    'waybill_url' => self::create_label($result->tmxAWB),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Tamex " . json_encode($result->data),
                    'status' => 'error'
                ];
                Log::alert("Error from Tamex : " . $order->MLVID . ' ' . json_encode($result->data));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Tamex",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Tamex: " . $order->MLVID);
            return $data;
        }
    }

    public static function create_return_shipment($order)
    {
        dd($order);
        $name = $order->fname . ' ' . $order->lname;
     
        $data = [
            'apikey' => self::$Prod_API_KEY,
            'pack_type' => '1',
            'pack_awb' => '',
            'pack_vendor_id' => $name,
            'pack_reciver_name' => 'isnaad',
            'pack_reciver_phone' => '966537737764',
            'pack_reciver_country' => 'sa',
            'pack_reciver_city' => 'riyadh',
            'pack_reciver_dist' => 'Al Mishael sulay'.'_'.'Istanbul St.',
            'pack_desc' => '',
            'pack_num_pcs' => $order->Qty_Item,
            'pack_weight' => $order->weight,
            'pack_cod_amount' => 0,
            'pack_currency_code' => 'SAR',
            'pack_extra_note' => '',
            'pack_live_time' => '4',
            'pack_sender_name' => $name,
            'pack_sender_phone' => $order->phone,
            'pack_sender_email' => '',
            'pack_send_country' =>'sa',
            'pack_send_city' =>  $order->city,
            'pack_sender_dist' => $order->address_1,
            'pack_sender_street' => $order->address_2,
            'pack_sender_zipcode' => '',
            'pack_reciver_email' => 'I.ansari@isnaad.sa',
            'pack_reciver_street' =>'Istanbul St.',
            'pack_reciver_zipcode' =>'11491',
            'pack_reciver_building' => '',
            'pack_dimention' => '' . 10 . ':' . 10 . ':' . 10,
        ];
        $data = json_encode($data);
        $result = self::send_request($data, 'create', 'POST');

        //  dd($result);
        if ($result) {
            if ($result->code == 0) {
                $data = [
                    'tracking_number' => $result->tmxAWB,
                    'waybill_url' => self::create_label($result->tmxAWB),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Tamex " . json_encode($result->data),
                    'status' => 'error'
                ];
                Log::alert("Error from Tamex : " . $order->order_number . ' ' . json_encode($result->data));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Tamex",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Tamex: " . $order->order_number);
            return $data;
        }
    }

    public static function create_label($pack_awb)
    {
        $data = [
            'apikey' => self::$Prod_API_KEY,
            'pack_awb' => $pack_awb,
        ];
        $data = json_encode($data);
        $result = self::send_request($data, 'print', 'GET');
        $filename = 'tamex-'.$pack_awb.'.pdf';
        file_put_contents(getcwd().'/Tamex_labels'. "/".$filename, $result);
        $file =Url('/').'/Tamex_labels'. "/".$filename;

        return $file;
    }

    public static function update_status($traking_number,$id){
/*
        $data = [
            'apikey' => self::$Prod_API_KEY,
            'pack_awb' => $traking_number,
        ];
        $data = json_encode($data);
        $result = self::send_request($data, 'status', 'GET');
        $result = json_decode($result);
    // dd($result);
       // dd( $result);
        if($result->status=='successful'){
            $order=order::find($id);
            $order->order_status='Delivered';
            $order->delivery_date=self::checkDate($result->UpdateOn, $order);
            $order->Last_Status=$result->message;
            $order->save();
         //   Log::alert("Delivered Tamex : " .$id);
        }elseif($result->status=='RTO'){
          // $order=order::find($id);
           // $order->order_status='Returned';
          //  $order->Last_Status=$result->message;
           // $order->save();
            Log::alert("Returned  Tamex : " .$id);
        }else{
            $order=order::find($id);
            $order->order_status='inTransit';
            $order->Last_Status=$result->message;
            $order->save();
            Log::alert("inTransit  Tamex : " .$id);
        }
*/
    }

    private static function checkDate($date, $order)
    {
       // dd($order->delivery_date != date("Y-m-d", strtotime($date)));
       if($order->delivery_date != date("Y-m-d", strtotime($date))){
             Log::alert(" delivery_date change : " .$order->order_number);
       }
    //    Log::alert("del  Tamex : ");
    return date("Y-m-d", strtotime($date));
    }
}
