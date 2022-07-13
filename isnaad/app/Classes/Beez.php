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

class Beez
{


    static $test_base_url = 'https://dev.aymakan.com.sa/api/v2/shipping/';
    static $prod_base_url = 'https://aymakan.com.sa/api/v2/shipping/';
    static $awb_url = 'https://beezerp.com/label/?t=';


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
        $store = store::where('account_id', $order->AcountID)->first();

        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $is_cod = ($cod_amount > 0) ? 1 : 0;
        try {
            $datetime = '';
            $datetime = (new Carbon($datetime))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
        }
       $data= [
            "LineItems" =>
               [
                    [
                    "ProductName" => $order->description_total,
                    "Quantity" => $order->Qty_Item,
                    "SKU" => $order->All_Sku,
                    "UPC" => "",
                    "GiftWrapping" => false,
                    "Description" => $order->description_total
                ]
               ],
            "Edit" => false,
            "Payment" => false,
           "PaymentAmount" => 0.00,
            "TrackingNumber"=> "",
            "AccountNumber" => "1200603",
            "ApiKey" => "ZUoLi7uzjEUrwS1XRiU71VRfSl7iVZcL",
            "RequestedBy" => $order->sender_name,
            "OrderNumber" => $order->orderNum,
            "Shipping" => true,
            "ShipmentType" => "C",
            "CustomerNote" => $order->CustComments,
            "Description" => $order->description_total,
            "COD" => $cod_amount,
            "PickupLocation" => "24.630062,46.8400283",
            "BillingAddress" =>
                [[
                    "CustomerFirstname" =>$order->custFName ,
                    "CustomerLastname" =>  $order->custLName==''?$order->custFName:$order->custLName,
                    "CustomerEmail" =>$order->custEmail==''? $order->sender_email:$order->custEmail,
                    "CustomerPhone1" => '+'.$order->custPhone,
                    "CustomerPhone2" => '+'.$order->custPhone,
                    "Lat" => "0.00",
                    "Lng" => "0.00",
                    "Line1" => $order->custAddress1,
                    "Line2" => $order->custAddress2,
                    "District" => $order->custState,
                    "City" => $order->custCity,
                    "PostCode" => $order->custZip,
                    "Country" => "Saudi Arabia"

                 ] ],
            "ShippingAddress" =>
                [[

                    "CustomerFirstname" =>$order->custFName ,
                    "CustomerLastname" => $order->custLName==''?$order->custFName:$order->custLName,
                    "CustomerEmail" => $order->custEmail==''? $order->sender_email:$order->custEmail ,
                    "CustomerPhone1" => '+'.$order->custPhone,
                    "CustomerPhone2" => '+'.$order->custPhone,
                    "Lat" => "0.00",
                    "Lng" => "0.00",
                    "Line1" => $order->custAddress1,
                    "Line2" => $order->custAddress2,
                    "District" => $order->custState,
                    "City" => $order->custCity,
                    "PostCode" => $order->custZip,
                    "Country" => "Saudi Arabia"

                 ] ]
        ];

        $data = json_encode($data);
   //  dd( $data);
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://beezlspwebapi.azurewebsites.net/api/Orders/PostOrder",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>$data,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$response=json_decode($response);
//dd($response);
        if ($response) {
                $data = [
                    'tracking_number' =>$response ,
                    'waybill_url' => self::$awb_url.$response,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
             
                return $data;
            
              
            } else {
                $data = [
                    'msg' => "Error from Beez " . $order->MLVID,
                    'status' => 'error'
                ];
                return $data;
        }

    }


    public static function update_status($tracking_number, $id)
    {
        // dd('asdasd');
        $result = self::send_request([], 'track/' . $tracking_number, 'GET');
        //dd($result);
        // $result = json_decode($result);

        if ($result->data->shipments[0]->status == 'Delivered') {

            $order = order::find($id);
            $order->order_status = 'Delivered';
            $order->delivery_date = $result->data->shipments[0]->delivery_date;
            $order->Last_Status = $result->data->shipments[0]->status;
            $order->save();

        } elseif ($result->data->shipments[0]->status == 'Returned') {
            $order = order::find($id);
            $order->order_status = 'Returned';
            $order->Last_Status = $result->data->shipments[0]->status;
            $order->save();
        } else {
            $order = order::find($id);
            $order->order_status = 'inTransit';
            $order->Last_Status = $result->data->shipments[0]->status;
            $order->save();
        }
    }

     private static function checkDate($date)
    {
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
