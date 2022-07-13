<?php

namespace App\Classes;

use App\carrier;
use App\carrier_city;
use App\city;
use App\store;
use App\order;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Log;
use Mpdf\Tag\Time;
use App\Helpers\update_stores;
class Aymakan extends update_stores{


    static $test_base_url = 'https://dev.aymakan.com.sa/api/v2/shipping/';
    static $prod_base_url = 'https://aymakan.com.sa/api/v2/shipping/';


    public static function send_request($data, $end_point, $request_type)
    {
        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'dc539179e7db4da567a2ee9cce7c967d-36308f5e-fba9-4039-b232-ce7f2d1405f5-f307bf46117dc1be06e6ba2a5fb40745/fdecf9f180213d734f9c0e21905c59b1/25d28563-9194-4666-bbc6-f7c1d5023aa0');

        $client = new GuzzleHttpClient();
        $res = '';
        try {
            if ($request_type == 'POST') {
                $res = $client->$request_type(self::$prod_base_url . $end_point, [
                    'headers' => $header,
                    'body' => $data
                ]);
                // dd($data);
            } else {
                $res = $client->$request_type(self::$prod_base_url . $end_point, [
                    'headers' => $header,
                    // 'body' => $data
                ]);
            }
            return json_decode($res->getBody()->getContents());
        } catch (\Exception $exception) {
            Log::error('error in send request for Aymakan ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        //dd(123);
        //dd($order,'aymkan');
        $store = store::where('account_id', $order->AcountID)->first();
        //dd($order->custCity);
        $city = city::where('name', $order->custCity)->where('country_id', 1)->first();
        $carrier_city = carrier_city::where('city_id', $city->id)->where('carrier_id', 4)->first();
        if (!$carrier_city) {
            Log::error( 'city ID not found Aymakan for order');
            $data = [
                'msg' => "city ID not found Aymakan for order " ,
                'status' => 'error'
            ];
            return $data;
        }
        $order->custCity = $carrier_city->name;
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $is_cod = ($cod_amount > 0) ? 1 : 0;
        try {
            $datetime = '';
            $datetime = (new Carbon($datetime))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
        }
        $data = [
            'fulfilment_customer_name' => $store->name,
            'requested_by' => $order->sender_name,
            'declared_value' => $order->declared_total,
            //'reference' => $order->MLVID.'_'.$order->ID,
            'is_cod' => $is_cod,
            'cod_amount' => $cod_amount,
            'currency' => 'SAR',
            'delivery_name' => $order->custFName,
            'delivery_email' => $order->custEmail,
            'delivery_city' => $order->custCity,
            'delivery_address' => $order->custAddress1,
            'delivery_region' => $order->custAddress2,
            'delivery_postcode' => $order->custZip,
            'delivery_country' => $order->custCountry,
            'delivery_phone' => $order->custPhone,
            'delivery_description' => $order->description_total,
            'collection_name' => $order->sender_name,
            'collection_email' =>$order->sender_email,
            'collection_city' => 'riyadh',
            'collection_address' => 'Al Mishael sulay',
            'collection_region' => 'Istanbul St.',
            'collection_postcode' => '11491',
            'collection_country' => 'SA',
            'collection_phone' => '966537737764',
            'pickup_date' => $datetime,
            'weight' => $order->WeightSum,
            //'pieces' => $order->Qty_Item,
            'pieces' => 1,
            'items_count' =>1,
            // 'items_count' => $order->items,
        ];
         //dd($data);
        $data = json_encode($data);
//dd($data);
        $result = self::send_request($data, 'create', 'POST');
        // $result = json_decode($result);
        
  //       dd($result);
        if ($result) {
            if ($result->success == "true") {
                $data = [
                    'tracking_number' => $result->data->shipping->tracking_number,
                    'waybill_url' => $result->data->shipping->pdf_label,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Aymamkan " ,
                    'status' => 'error'
                ];
                Log::alert("Error from Aymamkan : " . $order->MLVID);
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Aymakan",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Aymamkn: " . $order->MLVID);
            return $data;
        }
    }

    public static function create_return_shipment($order)
    {
        try {
            $datetime = '';
            $datetime = (new Carbon($datetime))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
        }
        $name = $order->fname . ' ' . $order->lname;
        $data = [
            'fulfilment_customer_name' => 'isnaad',
            'requested_by' => 'isnaad',
            'declared_value' => '0',
            //'reference' => $order->MLVID.'_'.$order->ID,
            'is_cod' => '0',
            'cod_amount' => '0',
            'currency' => 'SAR',
            'delivery_name' =>  'isnaad',
            'delivery_email' => 'I.ansari@isnaad.sa',
            'delivery_city' => 'Riyadh',
            'delivery_address' => 'Al Mishael sulay',
            'delivery_region' => 'Istanbul St.' ,
            'delivery_postcode' => '11491',
            'delivery_country' => 'SA',
            'delivery_phone' => '966537737764',
            'delivery_description' => $order->description,
            'collection_name' => $name,
            'collection_email' =>'I.ansari@isnaad.sa',
            'collection_city' => $order->city,
            'collection_address' => $order->address_1 ,
            'collection_region' => $order->address_2,
            'collection_postcode' => '00000',
            'collection_country' => 'SA',
            'collection_phone' => $order->phone,
            'pickup_date' => $datetime,
            'weight' => $order->weight,
            'pieces' => '1',
            'items_count' => '1',
        ];
        // dd($data);
        $data = json_encode($data);

        $result = self::send_request($data, 'create', 'POST');
        // $result = json_decode($result);
        // dd($result);
        if ($result) {
            if ($result->success == "true") {
                $data = [
                    'tracking_number' => $result->data->shipping->tracking_number,
                    'waybill_url' => $result->data->shipping->pdf_label,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Aymamkan " ,
                    'status' => 'error'
                ];
                Log::alert("Error from Aymamkan : " . $order->order_number);
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Aymakan",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Aymamkn: " . $order->order_number);
            return $data;
        }
    }

    public static function create_label($pack_awb)
    {
        $data = [
            'apikey' => '45615da2b783af18097c531e6d72a07a',
            'pack_awb' => $pack_awb,
        ];
        //19157338871687
        $data = json_encode($data);
        $result = self::send_request($data, 'print', 'GET');
       // dd($result);
        return $result;
    }

    public static function update_status($tracking_number,$id){

        $update_stores = new update_stores();
        $result = self::send_request([], 'track/'.$tracking_number, 'GET');
        //dd($result);
        // $result = json_decode($result);
      
//dd($newDate);
        if($result->data->shipments[0]->status=='Delivered'){
//sleep(500);
            $order=order::find($id);
            $order->order_status='Delivered';
            $order->delivery_date=$result->data->shipments[0]->delivery_date;
            $order->Last_Status=$result->data->shipments[0]->status;
            $order->carrier_charge=self::chargWehnReturn($order);
            $order->save();
            Log::alert("deliverid aymakan" . $id);
        }elseif($result->data->shipments[0]->status=='Returned'){
        $order=order::find($id);
           $newDate = date("Y-m-d", strtotime($result->data->shipments[0]->tracking_info[0]->created_at));
          //  $return_date=Carbon::now()->format('Y-m-d');
            $order->update(['return_date_carrier'=>$newDate]);
            Log::alert("Returned aymakan" . $id);
        }else{
            $order=order::find($id);
            $order->order_status='inTransit';
            $order->Last_Status=$result->data->shipments[0]->status;
            $order->save();
            Log::alert("inTransit aymakan" . $id);
        }
    }
    private static function chargWehnReturn($order){
        if($order->cod_amount>0){
            $carrier_charge = $order->carrier_charge-(.01*$order->cod_amount);
            return $carrier_charge+23;
        }
        return  $order->carrier_charge+23;
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
