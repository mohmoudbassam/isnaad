<?php

namespace App\Http\Controllers\integtation;

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
use App\deliverd_orders;
use phpDocumentor\Reflection\Types\This;

class SharyController extends Controller
{

    public static $url = "https://shari.sa/api/isnadd/webhook/shipping";


 public function update_status(){
     {
         
         $orders=order::doesnthave('deliverd_orders')->where([['store_id', '2'], ['processing_status', '0'],['active','1'],['order_number',138]])->get();


         $carriers = \App\carrier::all();
         foreach ($orders as $order) {
             foreach ($carriers as $carrier) {
                 if ($order->carrier == $carrier->name) {
                     $status=$this->getStatus($order->order_status);
                     $tracking_link=$carrier->tracking_link.$order->tracking_number;
                     $curl = curl_init();
                     $status=$this->getStatus($order->order_status);
                     $trLink='http://portal.isnaad.sa/'.$order->order_number;
                     if($status=="in Transit"){
                         $data = [
                             "auth-token" => "LetMeIn99^88!776",
                             "order_id" => $order->order_number,
                             "status" =>$status,
                             "tracking_url" => $carrier->tracking_link.$order->tracking_number,
                             "tracking_number" => $order->tracking_number,
                             "with_notification" =>true,
                             "shipping_company"=>$carrier->name ,

                             "note"=>"شركة الشحن :$carrier->name
                             رابط التتبع:$trLink
                            "

                         ];
                     }else{
                         $data = [
                             "auth-token" => "LetMeIn99^88!776",
                             "order_id" => $order->order_number,
                             "status" =>$status,
                             "tracking_url" => $carrier->tracking_link.$order->tracking_number,
                             "tracking_number" => $order->tracking_number,
                         ];
                     }
                     $data=json_encode($data);

                    curl_setopt_array($curl, array(
  CURLOPT_URL => "https://shari.sa/api/isnadd/webhook/shipping",
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
                     dd( $response);
                     $response=  json_decode($response);
                     
                     if($response->status==200){
                         if($order->order_status=='Delivered' ||$order->order_status=='Returned'){
                             deliverd_orders::create([
                                 'order_id' =>$order->id
                             ]);
                         }
                     }
                     curl_close($curl);
                     if (isset($response->status)){
                         //  Log::error('response' .$response->status. $order->order_number);
                     }else{
                         Log::error('error' . $order->id);
                     }
                 }
             }
         }


}
 }

    private function getStatus($status){
        if($status=='Delivered'|| $status=='delivered'){
            return "Delivered ";
        }elseif ($status=='Returned'){
            return "Returned";
        }else{
            return "in Transit";
        }
    }


}
