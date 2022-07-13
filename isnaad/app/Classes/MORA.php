<?php

namespace App\Classes;

use App\carrier;
use App\carrier_city;
use App\city;
use App\order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MORA
{


    static $prod_base_url = 'https://api.fastcoo-tech.com/API_v2/CreateOrder';
    static $secret_key = 'aa1ca7-33633d-b2cca3-66ad5a-991dfe';
    static $customerId = '10071949';

    public static function create_shipment($order)
    {
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $BookingMode = ($cod_amount == 0) ? 'CC' : 'COD';
        $data = [
            'format' => 'json',
            'method' => 'CreateOrder',
            'customerId' => self::$customerId,
            'secret_key' => self::$secret_key,
            'param' => [
                'sender_name' => $order->sender_name,
                'sender_email' => $order->sender_email,
                'origin' => 'Riyadh',
                'sender_phone' => '966537737764',
                'sender_address' => 'Al Mishael sulay' . 'Istanbul St.',
                'receiver_name' => $order->custFName . ' ' . $order->custLName,
                'receiver_phone' => $order->custPhone,
                'receiver_email' => '',
                'destination' => $order->custCity,
                'BookingMode' => $BookingMode,
                'codValue' => $cod_amount,
                'receiver_address' => $order->custAddress1,
                'reference_id' => $order->MLVID,
                'productType' => 'Parcel',
                'description' => $order->description_total,
                'pieces' => '1',
                'weight' => $order->WeightSum
            ]
        ];
            $headers = array(
 'Content-Type: application/json'
);
        $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, self::$prod_base_url);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

  
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);
//dd($result);
        if ($result) {
            if (isset($result->awb_no)) {
                $data = [
                    'tracking_number' => $result->awb_no,
                    'waybill_url' => $result->label_print,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                // dd($data);
                return $data;
            } else {
                $data = [
                    'msg' => "Error from mora " . json_encode($result->status),
                    'status' => 'error'
                ];
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to mora",
                'status' => 'error'
            ];
            return $data;
        }
    }

    public static function update_status($tracking_number, $id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fastcoo-tech.com/API/trackShipment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('awb' => $tracking_number),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);

        if ($result) {
            $count = count($result->travel_history);
            if ($result->travel_history[$count - 1]->code == 'POD') {
               // dd(date("Y-m-d", strtotime($result->travel_history[$count - 1]->entry_date)));
                $order = order::find($id);
                $order->order_status = 'Delivered';
                $order->delivery_date = date("Y-m-d", strtotime($result->travel_history[$count - 1]->entry_date));
                $order->Last_Status = $result->travel_history[$count - 1]->new_status;
                $order->save();
            } elseif ($result->travel_history[$count - 1]->new_status == 'Return') {
                $order = order::find($id);
                $order->return_date_carrier = date("Y-m-d", strtotime($result->travel_history[$count - 1]->entry_date));
                $order->Last_Status = $result->travel_history[$count - 1]->Activites;
                $order->save();
            } else {
                $order = order::find($id);
                $order->Last_Status = $result->travel_history[$count - 1]->new_status;
                $order->save();
            }
        }

    }
}
