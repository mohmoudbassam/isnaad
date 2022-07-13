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
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Self_;
use Storage;
use File;
use App\Helpers\RotateImage;
use App\Helpers\GeneratePDF;

class Aja
{

    static $username = 'isnaadsa';
    static $password = 'Isnaad.sa@2021';
    static $AccessLicenseNumber = 'DDAB0D6706934DD1';
    static $url = "https://ajalogistic.com/api/account/";
    static $testurl = "https://test.ajalogistic.com/api/account/";


    public static function Login()
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$url . 'Authenticate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
"UsernameOrEmailAddress":"nlulu@isnaad.sa",
"Password": "123456"
}
',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: Abp.Localization.CultureName=ar-EG'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);

        return $result->result;


    }

    public static function create_shipment($order)
    {

//dd(123);
        $city = city::where('name', $order->custCity)->where('country_id', 1)->first();
        $carrier_city = carrier_city::where('city_id', $city->id)->where('carrier_id', 24)->first();
        if (!$carrier_city) {
            Log::error('city ID not found aja for order ' . $order->MLVID);
            $data = [
                'msg' => "city ID not found aja for order " . $order->MLVID,
                'status' => 'error'
            ];
            return $data;
        }


        $city_code = $carrier_city->carrier_city_id;
        $date = Carbon::now()->format('Y-m-d H:i:s');
        //dd($date);
        $ServiceId = ($order->WeightSum < 15) ? 22 : 114;
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $data = array(
            'SenderCityId' => '5',
            'SenderTelephone' => '966537737764',
            'SenderMobile' => '966537737764',
            'RecipiantName' => $order->custFName,
            'CityId' => $city_code,
            'ShipmentDate' => $date,
            'RecipiantTelephone' => $order->custPhone,
            'RecipiantMobile' => $order->custPhone,
            'RecipiantAddress' => $order->custAddress1,
            'ServiceId' => $ServiceId,
            'Weight' => $order->WeightSum,
            'Pieces' => '1',
            'ShipmentDescription' => $order->description_total,
            'TotalValue' => $order->declared_total,
            'TranceportationsMode' => '4',
            'CodValue' => $cod_amount
        );

        $final_data = json_encode($data);
        //dd($final_data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$testurl . 'SendShipment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $final_data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . self::Login(),
                'Content-Type: application/json',
                'Cookie: Abp.Localization.CultureName=ar-EG'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
//dd($response);
        $result = json_decode($response);
       // dd($result,$order->MLVID);
        if ($result) {
            if (isset($result->trackNo)) {
                if ($result->success == 'true') {
                    $track = $result->trackNo;
                    $data = [
                        'tracking_number' => $track,
                        'waybill_url' => $result->printUrl,
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];
                    // dd($data);
                    return $data;
                } else {
                    $data = [
                        'msg' => "Error from Aja " . json_encode($result),
                        'status' => 'error'
                    ];
                    return $data;
                }
            } else {
                $data = [
                    'msg' => "Error adding order to Aja",
                    'status' => 'error'
                ];
                return $data;
            }
        }

    }

    public static function update_status($tracking, $id)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onlinetools.ups.com/track/v1/details/' . $tracking,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'transactionSrc: isnaad',
                'AccessLicenseNumber: DDAB0D6706934DD1',
                'Username: isnaadsa',
                'Password: Isnaad.sa@2021',
                'transId: isnaad'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);


//dd(date("Y-m-d", strtotime($result->trackResponse->shipment[0]->package[0]->activity[0]->date)));
        if ($result->trackResponse->shipment[0]->package[0]->activity[0]->status->type == 'D') {
//sleep(500);
            $order = order::find($id);
            $order->order_status = 'Delivered';
            $order->delivery_date = date("Y-m-d", strtotime($result->trackResponse->shipment[0]->package[0]->activity[0]->date));
            $order->Last_Status = $result->trackResponse->shipment[0]->package[0]->activity[0]->status->description;
            $order->save();
            Log::alert("deliverid UPS" . $id);
        } elseif ($result->trackResponse->shipment[0]->package[0]->activity[0]->status->type == 'RS') {
            $order = order::find($id);
            $newDate = date("Y-m-d", strtotime($result->trackResponse->shipment[0]->package[0]->activity[0]->date));
            //  $return_date=Carbon::now()->format('Y-m-d');
            $order->update(['return_date_carrier' => $newDate]);
            Log::alert("Returned UPS" . $id);
        } else {
            $order = order::find($id);
            $order->order_status = 'inTransit';
            $order->Last_Status = $result->trackResponse->shipment[0]->package[0]->activity[0]->status->description;
            $order->save();
            Log::alert("inTransit UPS" . $id);
        }
    }

}
