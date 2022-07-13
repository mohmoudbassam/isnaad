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

class DOS {

    //static $test_base_url = 'https://app.shipsy.in/api/customer/integration/consignment/';
    static $prod_base_url = 'https://dos.fastcoo-solutions.com/lm';
  //  static $wblurl = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/shippinglabel/link?reference_number=';
  //  static $track_url= 'track?reference_number=';


    public static function send_request($data, $request_type)
    {
        
       // $client = new GuzzleHttpClient();
        $res = '';
        try {
                $client = new Client();
                
                $response = $client->request('GET', "https://dos.fastcoo-solutions.com/lm/shipmentBookingApi_lm.php", [
               "query" => $data,
                  ]);
                  // dd( json_decode( $response->getBody() ));
                $body = $response->getBody();
                $arr_body = json_decode($body);
                return $arr_body;
        } catch (\Exception $exception) {
            Log::error('error in send request for DOS ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $BookingMode= ($cod_amount == 0 ) ? 'CC' : 'COD';
        $data = [
                    'sender_email' => 'i.ansari@isnaad.sa',
                    'password' => '1234567891',
                    'productType' => 'KVAIMI',
                    'service' => '4',
                    'Weight' => $order->WeightSum,
                    'Description' => $order->description_total,
                    'NumberOfParcel' => '1',
                    'BookingMode' => $BookingMode,
                    'codValue' => $cod_amount,
                    'Product_price' => '',
                    'refrence_id' => $order->MLVID,
                    'Receiver_name' => $order->custFName . ' ' . $order->custLName,
                    'Receiver_email' => $order->custEmail,
                    'Receiver_address' => $order->custAddress1,
                    'Receiver_phone' => $order->custPhone,
                    'Reciever_city' => $order->custCity,
                    'sender_name' => $order->sender_name,
                    'sender_address' => 'Al Mishael sulay'.'Istanbul St.',
                    'sender_mobile' => '966537737764',
                    'sender_city' => 'riyadh',

        ];

        
//dd($order);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =>'https://dos.fastcoo-solutions.com/lm/shipmentBookingApi_lm.php?'.http_build_query($data),
   CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response);
//dd($result);

       // $data = json_encode($data);
       // $result = self::send_request($data, 'GET');
        //dd($result);
        if ($result) {
            if (isset($result->awb)) {
                $data = [
                    'tracking_number' => $result->awb,
                    'waybill_url' => $result->awb_print_url,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                // dd($data);
                return $data;
            } else {
                $data = [
                    'msg' => "Error from DOS " . json_encode($result->data),
                    'status' => 'error'
                ];
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to DOS",
                'status' => 'error'
            ];
            return $data;
        }
    }
    public static function update_status($tracking_number,$id){
        
        $data = [
            'awb_no'=>$tracking_number
        ];
        $result = self::send_request($data, 'GET');
        if($result->travel_history[0]->code=='POD' || $result->travel_history[0]->new_status=='Delivered'){
            $order=order::find($id);
           // $order->order_status='Delivered';
            $date=$result->travel_history[0]->entry_date;
            $timestamp = strtotime($date);
            $newdate= date("Y-m-d",$timestamp );
            if($order->delivery_date!=$newdate){
                 $order->delivery_date=$newdate;
                 $order->save();
                 Log::alert("deliverd dos updated: " . $id);
            }
           
           // $order->Last_Status= $result->travel_history[0]->new_status;
            

        }
        /*
        elseif($result->travel_history[0]->code=='RTO'){
            $order=order::find($id);
            $order->order_status='Returned';
            $order->Last_Status= $result->travel_history[0]->new_status;
            $order->save();
        }else{

            $order=order::find($id);
            $order->order_status='inTransit';
            $order->Last_Status= $result->travel_history[0]->new_status;
            $order->save();
        }
        */
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
