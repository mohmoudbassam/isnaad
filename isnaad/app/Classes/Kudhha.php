<?php

namespace App\Classes;


use App\carrier;
use App\city;
use App\city_name;
use App\carrier_city;
use App\order;
use App\store;
use http\Env\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Redirect;
use PDF;
use Carbon\Carbon;
use App\Helpers\update_stores;

class Kudhha {
    static $username = 'isnaad@kudhha.sa';
    static $password = 'Isnaad123';
    static $remember_me = 'true';
    //static $base_url= 'https://stagingapi.shipox.com/api/';
    static $base_url = 'https://prodapi.shipox.com/api/';

    public static function authorization()
    {
        $data = [
            'username' => self::$username,
            'password' => self::$password,
            'remember_me' => self::$remember_me
        ];
        try {
            $result = self::send_request(json_encode($data), 'v1/customer/authenticate', 'POST', 0);
            if ($result->status == 'success') {
                $id_token = $result->data->id_token;
                $Kudhha = carrier::where('name', 'Kudhha')->first();
                $Kudhha->token = $id_token;
                $Kudhha->expires_at = strtotime('+1 day');
                $Kudhha->save();
                return $id_token;
            } else {
                Log::error('error generating token for Kudhha');
                return 0;
            }
        } catch (\Exception $e) {
            Log::error('error generating token for Kudhha ' . $e->getMessage());
            return 0;
        }
    }

    public static function send_request($data, $end_point, $request_type, $token = 1)
    {
        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json');
        if ($token == 1) {
            $Kudhha = carrier::where('name', 'Kudhha')->first();
            if ($Kudhha->expires_at > time()) {
                $header['Authorization'] = 'Bearer ' . $Kudhha->token;
            } else {
                $header['Authorization'] = 'Bearer ' . self::authorization();
            }
        }
        $client = new GuzzleHttpClient();
        $res = '';
        try {
            if ($request_type == 'POST') {
                $res = $client->$request_type(self::$base_url . $end_point, [
                    'headers' => $header,
                    'body' => $data
                ]);
            } else {
                $res = $client->$request_type(self::$base_url . $end_point, [
                    'headers' => $header,
                    'form-params' => $data
                ]);
            }
            //  dd($res);
            return json_decode($res->getBody()->getContents());

        } catch (\Exception $exception) {
            Log::error('error in send request for Kudhha ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $payment_type = ($cod_amount > 0) ? "credit_balance" : "credit_balance";
        $payer = ($cod_amount > 0) ? "recipient" : "sender";
        $charge_items = array();
        if ($cod_amount > 0) {
            $charge_items[] = array(
                'charge_type' => 'cod',
                'charge' => $cod_amount,
                'payer' => 'recipient'
            );
        }
        $charge_items[] = array(
            'charge_type' => 'service_custom',
            'charge' => 0,
            'payer' => 'sender'
        );
        $data = [
            'sender_data' => [
                'address_type' => "business",
                'name' => $order->sender_name,
                'email' => $order->sender_email,
                'apartment' => '',
                'building' => '',
                'street' => 'Istanbul St.',
                'landmark' => 'Al Mishael sulay',
                'city' => ['code' => 'riyadh'],
                'country' => ['id' => 191],
                'phone' => '966537737764'
            ],
            'recipient_data' => [
                'address_type' => "business",
                'name' => $order->custFName,
                'email' => $order->custEmail,
                'apartment' => '',
                'building' => '',
                'street' => $order->custAddress1 . ' ' . $order->custAddress2,
                'landmark' => '',
                'city' => ['id' => '26148057'],
                'country' => ['id' => 191],
                'phone' => $order->custPhone
            ],
            'dimensions' => [
                'weight' => $order->WeightSum,
                'width' => '15',
                'length' => '15',
                'height' =>'15',
                'unit' => "METRIC",
                'domestic' => false
            ],
            'package_type' => [
                'courier_type' => 'INSIDE_RIYADH',
            ],
            'charge_items' => $charge_items,
            'recipient_not_available' => "do_not_deliver",
            'payment_type' => $payment_type,
            'payer' => $payer,
            'parcel_value' => $order->declared_total,
            'fragile' => true,
            'note' => '',
            'piece_count' => '1',
            'force_create' => true,
            'reference_id' => $order->MLVID,
        ];
        $data = json_encode($data);
        // dd($data);
        $result = self::send_request($data, 'v2/customer/order', 'POST');
       // dd( $result);
        if ($result) {

            if ($result->status == "success") {
                $data = [
                    'tracking_number' => $result->data->order_number,
                    'waybill_url' => route('KudhhaLabel',array('tracking_num' =>$result->data->order_number)),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Kudhha " . json_encode($result->message),
                    'status' => 'error'
                ];
                Log::alert("Error from Kudhha : " . $order->MLVID . ' ' . json_encode($result->message));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Kudhha",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Kudhha: " . $order->MLVID);
            return $data;
        }
    }

    public static function create_return_shipment($order)
    {
        $name = $order->fname . ' ' . $order->lname;
        $payment_type = "credit_balance";
        $payer = "sender";
        $charge_items = array();
        $charge_items[] = array(
            'charge_type' => 'service_custom',
            'charge' => 0,
            'payer' => 'recipient'
        );
        $data = [
            'sender_data' => [
                'address_type' => "business",
                'name' =>  $name ,
                'email' => '',
                'apartment' => '',
                'building' => '',
                'street' => $order->address_1,
                'landmark' => $order->address_2,
                'city' => ['code' => 'riyadh'],
                'country' => ['id' => 191],
                'phone' => $order->phone
            ],
            'recipient_data' => [
                'address_type' => "business",
                'name' => 'isnaad',
                'email' => 'I.ansari@isnaad.sa',
                'apartment' => '',
                'building' => '',
                'street' => 'Istanbul St.' . ' ' . 'Al Mishael sulay',
                'landmark' => '',
                'city' => ['id' => 26148057],
                'country' => ['id' => 191],
                'phone' => '966537737764'
            ],
            'dimensions' => [
                'weight' => $order->weight,
                'width' => '10',
                'length' => '10',
                'height' => '10',
                'unit' => "METRIC",
                'domestic' => false
            ],
            'package_type' => [
                'courier_type' => 'INSIDE_RIYADH',
            ],
            'charge_items' => $charge_items,
            'recipient_not_available' => "do_not_deliver",
            'payment_type' => $payment_type,
            'payer' => $payer,
            'parcel_value' => '0',
            'fragile' => true,
            'note' => '',
            'piece_count' => '',
            'force_create' => true,
            'reference_id' => $order->order_number,
        ];
        $data = json_encode($data);
        //  dd($data);
        $result = self::send_request($data, 'v2/customer/order', 'POST');
        if ($result) {

            if ($result->status == "success") {
                $data = [
                    'tracking_number' => $result->data->order_number,
                    'waybill_url' => route('KudhhaLabel',array('tracking_num' =>$result->data->order_number)),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Kudhha " . json_encode($result->message),
                    'status' => 'error'
                ];
                Log::alert("Error from Kudhha : " . $order->order->order_number . ' ' . json_encode($result->message));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Kudhha",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Kudhha: " . $order->order_number);
            return $data;
        }
    }

    public static function create_label($tracking_number)
    {
        $id='1067396147';
        $result = self::send_request([], '/v1/customer/orders/airwaybill_mini?ids=' . $id . '&order_numbers=' . $tracking_number, 'GET');
        //dd($result);
        if ($result->status == "success") {
            //dd($result->data->value);
            return $result->data->value;

        } elseif($result->status == "401") {

            return '';
        }
    }

    public static function update_status($tracking_number,$id){

        $result = self::send_request([], '/v1/customer/order/'.$tracking_number.'/history_items', 'GET');

        //  dd($newDate);
        if(isset($result->status)){
            if ($result->status == "success") {

                // dd($result->data->list[0]->status);
                if($result->data->list[0]->status=="completed"){
                    $originalDate=$result->data->list[0]->date;
                    $newDate = date("Y-m-d", strtotime($originalDate));

                    $or=order::find($id);
                    // dd($result->data->list[0]->status);
                    $order=$or->update([
                        'order_status'=>'Delivered',
                        'delivery_date'=>$newDate,
                        'Last_Status'=>$result->data->list[0]->status
                    ]);

                    Log::alert("deliverd Kudhha: " . $id);
                }elseif($result->data->list[0]->status=='returned_to_origin'){
                    $originalDate=$result->data->list[0]->date;
                    $newDate = date("Y-m-d", strtotime($originalDate));;
                    $order=order::find($id)->update(['return_date_carrier'=>$newDate]);

                    Log::alert("Returned Kudhha: " . $id);
                }else{

                    $order=order::find($id)->update(['order_status'=>'inTransit','Last_Status'=>$result->data->list[0]->status]);

                    Log::alert("inTransit Kudhha: " . $id);
                }
            } else {
                Log::error('error in order ' . $id);

            }
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
