<?php

namespace App\Http\Controllers\integtation;
use App\Helpers\update_stores;
use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class StoresController extends Controller
{

    public function update_status_Sadatalbukhur($order_num,$status)
    {

        $order = order::where([['store_id', '5'], ['processing_status', '0'],['order_number',$order_num]])->first();

        $carriers = \App\carrier::all();

        foreach ($carriers as $carrier) {
            if ($order->carrier == $carrier->name) {
                $status=$this->getStatus_salla($order->order_status);
                $tracking_link=$carrier->tracking_link.$order->tracking_number;
                $curl = curl_init();
                $array=[
                    'auth-token'=>'IrwpV6OTf6FT2ASfc5mBct6EGBl',
                    'status'=>(int)$status,
                    'tracking_url'=>$tracking_link,
                    'tracking_number'=>$order->tracking_number,

                ];
                $data=json_encode($array);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://s.salla.sa/api/webhook/isnaad/order/".$order->order_number,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                    ),
                ));

                $response = curl_exec($curl);
                $response=  json_decode($response);

//dd($response);
                curl_close($curl);




            }
        }
    }

    public function update_status_JAWANI($order_num,$status)
    {
        $orders = order::where([['store_id', '9'], ['processing_status', '0'],['created_at','>=','2020-05-23']])->get();
        //   dd(     $orders);

        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $curl = curl_init();
                    $data = [
                        "auth-token" => "IrwpV6OTf6FT2ASfc5mBct6EGBl",
                        "order_id" => $order->order_number,
                        "status" =>(int)$this->getStatus_salla($order->order_status),
                        "tracking_url" => $carrier->tracking_link.$order->tracking_number,
                        "tracking_number" => $order->tracking_number,
                    ];
                    $data=json_encode($data);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://s.salla.sa/api/webhook/isnaad/order/".$order->order_number,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $data,
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
//dd($response);
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Snackches($order_num,$status)
    {
        $orders = order::where([['store_id', '10'], ['processing_status', '0']])->whereNotNull('shipping_date')->get();


        $carriers = \App\carrier::all();


        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {

                    $curl = curl_init();
                    $status=$this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>"{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$carrier->tracking_link.$order->tracking_number\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_robil($order_num,$status)
    {
        $orders = order::where([['store_id', '11'], ['processing_status', '0']])->whereNotNull('shipping_date')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {

                    $curl = curl_init();
                    $status=$this->getStatus_zid($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>"{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$carrier->tracking_link.$order->tracking_number\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                }
            }
        }
    }
    public function update_status_Sorrah($order_num,$status)
    {
        $orders = order::where([['store_id', '13'], ['processing_status', '0']])->whereNotNull('shipping_date')->get();


        $carriers = \App\carrier::all();


        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {

                    $curl = curl_init();
                    $status=$this->getStatus_zid($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>"{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$carrier->tracking_link.$order->tracking_number\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                }
            }
        }
    }
    public function update_status_wix($order_num,$status)
    {
        $order = order::where([['processing_status','0'],['order_number',$order_num]])->first();
        //   dd(     $orders);

        $carriers = \App\carrier::all();

        foreach ($carriers as $carrier) {
            if ($order->carrier == $carrier->name) {
                $curl = curl_init();
                $data = [
                    "auth-token" => "IrwpV6OTf6FT2ASfc5mBct6EGBl",
                    "order_id" => $order->order_number,
                    "status" =>(int)$this->getStatus_salla($order->order_status),
                    "tracking_url" => $carrier->tracking_link.$order->tracking_number,
                    "tracking_number" => $order->tracking_number,
                ];
                $data=json_encode($data);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://s.salla.sa/api/webhook/isnaad/order/".$order->order_number,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                    ),
                ));

                $response = curl_exec($curl);
                $response=json_decode($response);

                if(!isset($response->status)){
                   // dd($response);
                //dd($order->order_number);
                 Log::error( 'error' . $order->order_number);
                }else{
                Log::error( $response->status .'NO' . $order->order_number);

                }

                curl_close($curl);

            }
        }
    }

private function getStatus_zid($status)
{
    if ($status == 'Delivered') {
        return "2";
    } elseif ($status == 'Returned') {
        return "3";
    } else {
        return "1";
    }
}
private function getStatus_salla($status){
    if($status=='Delivered'){
        return 2;
    }elseif ($status=='Returned'){
        return 3;
    }else{
        return 1;
    }
}



}
