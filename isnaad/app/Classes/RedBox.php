<?php

namespace App\Classes;
use App\carrier;
use App\city;
use App\city_name;
use App\carrier_city;
use App\order;
use App\store;
use App\Models\RedBox_id;
use http\Env\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Redirect;
use PDF;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Self_;
class RedBox

{
    static $testurl = 'https://stage.redboxsa.com/api/business/v1/';
    static $url = 'https://app.redboxsa.com/api/business/v1/';
    public Static function create_shipment($order){

        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;

        $data=
            array (
                'items' =>
                    array (
                        0 =>
                            array (
                                'name' => $order->description_total,
                                'quantity' => 1,
                                'description' => $order->description_total,
                                'unitPrice' => $order->declared_total,
                                'currency' => 'SAR',
                            ),
                    ),
                'reference' => $order->MLVID.'2',
                'sender_name' => $order->sender_name,
                'sender_email' => 'info@isnaad.sa',
                'sender_phone' => '966537737764',
                'sender_address' => 'Al Mishael sulay ,Istanbul St.',
                'customer_name' => $order->custFName ,
                'customer_email' => $order->custEmail,
                'customer_phone' => $order->custPhone,
                'customer_address' => $order->custAddress1,
                'dimension_unit' => 'cm',
                'dimension_heigh' => 20,
                'dimension_length' => 20,
                'dimension_width' => 20,
                'weight_unit' => 'kg',
                'weight_value' => $order->WeightSum,
                'cod_currency' => 'SAR',
                'cod_amount' => $cod_amount,
                'from_platform' => 'Isnaad',
            );
        $final_data=json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$url.'create-shipment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$final_data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJvcmdhbml6YXRpb25faWQiOiI2MWRlZTcyYjk0NzQ3NTBkYThiMTkxMjEiLCJrZXkiOiIyMDIyLTAxLTEyVDE0OjM2OjAyLjM2N1oiLCJpYXQiOjE2NDE5OTgxNjJ9.UAi8y28Vgz53wP9spemV8codVti4viOTd2MWCfoQxlY',
                'Content-Type: application/json'
            
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);
       // dd($result);
        if ($result) {
            if ($result->success  == 'true') {
                $track= $result->tracking_number;
                $data = [
                    'tracking_number' => $track,
                    'waybill_url' => $result->url_shipping_label,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                $RedBox = new RedBox_id();
                $RedBox->ship_num = $order->MLVID;
                $RedBox->shipment_id = $result->shipment_id;
                $RedBox->save();
                // dd($data);
                return $data;
            } else {
                $data = [
                    'msg' => "Error from RedBox " . json_encode($result->data),
                    'status' => 'error'
                ];
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to RedBox",
                'status' => 'error'
            ];
            return $data;
        }
    }
}
