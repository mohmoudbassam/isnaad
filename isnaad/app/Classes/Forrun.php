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
use phpDocumentor\Reflection\Types\Self_;
use App\Helpers\update_stores;
use Carbon\Carbon;
class Forrun extends update_stores{
    static $test_base_url = 'https://dots-lab.dots-solution.com/api/v2/';
    static $prod_base_url = 'https://tam2go.dots-solution.com/api/v2/';
    static $user_name = 'isnaad_api';
    static $password = 'KafSkfTgZq8d';

    public static function create_shipment($order)
    {
{

        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $curl = curl_init();

        $array=[
            'awb'=>'',
            'from_address'=>['name'=> $order->sender_name,'country'=>'sa','city'=>'riyadh','region'=>'riyadh','street'=>'Al Mishael sulay,Istanbul St.','building'=>'','level'=>'','apartment_number'=>'','zip_code'=>'11491','latitude'=>'','longitude'=>'','phone'=>'966537737764','email'=>$order->sender_email,'address_type'=>'','BUSINESS'=>'','preferred_time'=>''],
            'to_address'=>['name'=>$order->custFName,'country'=>$order->custCountry,'city'=>$order->custCity,'region'=>'riyadh' ,'street'=>$order->custAddress1,'building'=>'','level'=>'','apartment_number'=>'','zip_code'=>$order->custZip,'latitude'=>'','longitude'=>'','phone'=>$order->custPhone,'email'=>$order->custEmail,'address_type'=>'','HOME'=>'','preferred_time'=>''],
            "payment"=> ["currency"=>"SAR","cod_amount"=>  $cod_amount,"item_value"=> "$order->declared_total"],
             'package_details' =>['type'=>'DOMESTIC','items_description'=>$order->description_total,'weight'=>$order->WeightSum,'reference_no'=> $order->MLVID,'shipping_note'=>$order->orderComments,'number_packages'=>1]
        ];
       $payload=json_encode($array);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tam2go.dots-solution.com/api/v2/create_order/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$payload,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "username: isnaad_api",
                "password: 7KvXBNcq4E",
                "Authorization: Basic aXNuYWFkX2FwaTpLYWZTa2ZUZ1pxOGQ="
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
       // dd($result);
        if ($result) {
            if ($result->status == 'OK') {
                $data = [
                    'tracking_number' => $result->payload->awbs[0]->code,
                    'waybill_url' => $result->payload->awbs[0]->label_url,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Forrun " . json_decode($result->data),
                    'status' => 'error'
                ];
                Log::alert("Error from Forrun : " . $order->MLVID . ' ' . json_decode($result->data));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Forrun",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To Forrun: " . $order->MLVID);
            return $data;
        }
    }
    }

    public static function update_status($traking,$id){
        $order=order::find($id);

        $url='https://tam2go.dots-solution.com/api/v2/track_order?awb='.$traking;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL =>$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => array('' => ''),
            CURLOPT_HTTPHEADER => array(
                "Username: aXNuYWFkX2FwaTpLYWZTa2ZUZ1pxOGQ",
                "Password: 7KvXBNcq4E",
                "Authorization: Basic aXNuYWFkX2FwaTpLYWZTa2ZUZ1pxOGQ="
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response=json_decode($response);

        if($response->status=='OK'){
            $last_index=array_key_last($response->payload->statuses);
            $status=$response->payload->statuses[$last_index]->status;

            if($status=='DELIVERED'){
                $date=$response->payload->statuses[$last_index]->timestamp;
                //dd($date);
            if($order->delivery_date!= $date){
            $order->delivery_date=$date;
            $order->save();
            Log::alert("deliverd Forrun updated: " . $id);
            }
               // $newdate= self::checkDate($date,$order->store_id);
              //$order->order_status='Delivered';
               //$order->Last_Status=$status;
            // $order->delivery_date=$newdate;
               // $order->carrier_charge=self::carrer_charge($order);
             //  $order->save();
            }
            /*
            elseif($status=='RTO'){
           // $order->order_status='Returned';
           // $order->Last_Status=$status;
           // $order->save();
            }else{
            $order->order_status='inTransit';
            $order->Last_Status=$status;
            $order->save();
            }
*/
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
    public static function  carrer_charge($order){
        $carrier=  carrier::where('name','Forrun')->first();

        if($order->city=='riyadh' || $order->city=='Riyadh'){

            $price=$carrier->price_in_ryad;
            $last_price=$price;

        }else{
            $price=$carrier->price_out_ryad;
            $last_price=$price;
        }

        return  $last_price;
    }
}
