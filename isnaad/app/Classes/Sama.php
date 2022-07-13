<?php


namespace App\Classes;


use App\carrier;
use App\carrier_city;
use App\city;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use App\Helpers\update_stores;
use App\order;
class Sama extends update_stores{
    //static $test_base_url = 'http://test.til.sa/api/v2/';
    static $prod_base_url = 'https://samaest.co/services/';
    static $UID= '15';
    static $Prod_API_KEY= 'TMX247563ARTYU78XC87QW1236T39487';

    public static function send_request($data,$end_point, $request_type)
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
            Log::error('error in send request for Sama ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $paymode= ($cod_amount == 0) ? 'Credit' : 'Cash on Delivery' ;

        $pay_load=[
            'uid'=>'15','asn'=>$order->MLVID,'paymode'=>$paymode, 'amount'=>$order->declared_total,
            'OrderDate'=>$order->OrderDate,'orderNum'=>$order->orderNum,'orderRef'=>$order->orderRef, 'orderComments'=>$order->orderComments,
            'custEmail'=>$order->custEmail,'custPhone'=>$order->custPhone,'custFName'=>$order->custFName, 'custLName'=>$order->custLName,
            'custCompany'=>$order->custCompany ,'custAddress1'=>$order->custAddress1,'custAddress2'=>$order->custAddress2, 'custCity'=>$order->custCity,
            'custState'=>$order->custState ,'custZip'=>$order->custZip,'custCountry'=>$order->custCountry, 'CODamount'=>$cod_amount,
            'items'=>$order->items ,'shipping_carrier'=>$order->shipping_carrier,'Ship_Method'=>$order->Ship_Method, 'Qty_Item'=>$order->Qty_Item,
            'description_total'=>$order->description_total ,'WeightSum'=>$order->WeightSum,'declared_total'=>$order->declared_total, 'Length'=>$order->Length,
            'Width'=>$order->Width ,'Height'=>$order->Height,'CompanyName'=>$order->CompanyName, 'CustComments'=>$order->CustComments,
            'from_first_name'=>'Irshad Ansari' ,'from_last_name'=>'Irshad Ansari','from_mail'=>$order->from_mail, 'from_phone'=>'966537737764',
            'from_address1'=>'Al Mishael sulay' ,'from_address2'=>'Istanbul St.','from_city'=>'riyadh', 'from_zip'=>$order->from_zip,
            'from_country'=>'SA'
        ];
        $pay_load = json_encode($pay_load,JSON_UNESCAPED_UNICODE);

        // dd($pay_load);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://samaest.co/services/PostShipment.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $pay_load,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json;charset=utf-8"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //    dd($response);
        $response = json_decode($response);

        $a = new SimpleXMLElement($response[0]->awbpdfurl);
        //  dd($response[0]->"awb number");
        $awb=  $response[0]->{'awb number'};
        $awb_url= (string)$a['href'];
        if ($response) {
            if ($response[0]->status == 'SUCCESS') {
                $data = [
                    'tracking_number' => $awb,
                    'waybill_url' => $awb_url,
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                //    dd($data);
                return $data;
            } else {
                $data = [
                    'msg' => "Error from SAMA ",
                    'status' => 'error'
                ];
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to SAMA",
                'status' => 'error'
            ];
            return $data;
        }
    }


    public static function update_status($tracking_number,$id){
        $curl = curl_init();
//dd('gh');
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://samaest.co/services/getBookingStatus.php?tcode=".$tracking_number,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $response=  json_decode($response);
        $response = end($response);

        if(isset($response->Booking_Status)){
            $order=order::find($id);
            if($response->Booking_Status==='Package Delivered'){
                $Delivery_date= $response->UpdateTime;
                $order->order_status='Delivered';
                $Delivery_date = date("Y-m-d", strtotime($Delivery_date));
                dd($Delivery_date);
                $order->delivery_date=$Delivery_date;
                $order->Last_Status=$response->Booking_Status;
                $order->save();
            }elseif ($response->Booking_Status=='Return'){
                $order->order_status='Returned';
                $order->Last_Status=$response->Booking_Status;
                $order->save();
            }else{
                $order->order_status='inTransit';
                $order->Last_Status=$response->Booking_Status;
                $order->save();
            }
        }
        curl_close($curl);
        // dd($response);

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



