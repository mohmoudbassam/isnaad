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
class UPS
{
    use RotateImage,GeneratePDF;
    static $username = 'isnaadsa';
    static $password = 'Isnaad.sa@2021';
    static $AccessLicenseNumber = 'DDAB0D6706934DD1';
    static $url = "https://api.eirad.info/api/v1/shipment/generate";
    static $testurl = "https://1api.eirad.info/api/v1/shipment/generate";


    public static function create_shipment($order){

//dd(123);
        $city = city::where('name', $order->custCity)->where('country_id', 1)->first();
        $carrier_city = carrier_city::where('city_id', $city->id)->where('carrier_id', 23)->first();
        if (!$carrier_city) {
            Log::error( 'city ID not found ups for order '.$order->MLVID);
            $data = [
                'msg' => "city ID not found ups for order ". $order->MLVID ,
                'status' => 'error'
            ];
            return $data;
        }


        $postcode= $carrier_city->carrier_city_id;
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $referance1 = ($cod_amount == 0) ? 'Paid' : 'COD '.$cod_amount.' SAR' ;
        $data= array(
            'ShipmentRequest' =>
                array (
                    'Shipment' =>
                        array (
                            'Description' => $order->description_total,
                            'Shipper' =>
                                array (
                                    'Name' => $order->sender_name,
                                    'AttentionName' => $order->sender_name,
                                    'Phone' =>
                                        array (
                                            'Number' => '966537737764',
                                        ),
                                    'ShipperNumber' => 'W7705E',
                                    'Address' =>
                                        array (
                                            'AddressLine' => 'Al Mishael sulay ,Istanbul St.',
                                            'City' => 'Riyadh',
                                            'PostalCode' => '11111',
                                            'StateProvinceCode' => 'RUH',
                                            'CountryCode' => 'SA'
                                        ),
                                ),
                            'ShipTo' =>
                                array (
                                    'Name' => $order->custFName ,
                                    'AttentionName' => $order->custFName ,


                                    'Phone' =>
                                        array (
                                            'Number' => $order->custPhone,
                                        ),
                                    'Address' =>
                                        array (
                                            'AddressLine' => $order->custAddress1,
                                            'City' => $order->custCity,
                                            'CountryCode' => 'SA',
                                            'StateProvinceCode' => '',
                                            'PostalCode' => $postcode
                                        ),
                                ),
                            'ShipFrom' =>
                                array (
                                    'Name' => $order->sender_name,
                                    'AttentionName' => $order->sender_name,
                                    'Phone' =>
                                        array (
                                            'Number' => $order->custPhone,
                                        ),
                                    'Address' =>
                                        array (
                                            'AddressLine' => 'Al Mishael sulay ,Istanbul St.',
                                            'City' => 'Riyadh',
                                            'StateProvinceCode' => 'RUH',
                                            'CountryCode' => 'SA',
                                            'PostalCode' => '11111',
                                        ),
                                ),
                            'PaymentInformation' =>
                                array (
                                    'ShipmentCharge' =>
                                        array (
                                            'Type' => '01',
                                            'BillShipper' =>
                                                array (
                                                    'AccountNumber' => 'W7705E',
                                                ),
                                        ),
                                ),
                            'Service' =>
                                array (
                                    'Code' => '65',
                                    'Description' => 'Expedited',
                                ),
                            'Package' =>
                                array (
                                    array (
                                        'Packaging' =>
                                            array (
                                                'Code' => '02',
                                            ),
                                        'PackageWeight' =>
                                            array (
                                                'UnitOfMeasurement' =>
                                                    array (
                                                        'Code' => 'KGS',
                                                    ),
                                                'Weight' => $order->WeightSum.'',
                                            ),
                                    ),
                                ),
                            'ReferenceNumber' =>
                                array (
                                    0 =>
                                        array (
                                            'Value' => $referance1,
                                        ),
                                    1 =>
                                        array (
                                            'Value' =>  $order->MLVID,
                                        ),
                                ),
                        ),
                ),
        );

        $final_data=json_encode($data);
        //dd($final_data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $final_data,
            CURLOPT_HTTPHEADER => array(
                'transId: isnaad',
                'x-client-secret: UoY04fD0AJS9QiACJtUR3vbPBp1K1njlNpVuVmOR',
                'x-client-id: 18',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
//dd($response);
        $result = json_decode($response);
        //dd($result,$order->MLVID);
        if ($result) {
            if(isset($result->ShipmentResponse)){
                if ($result->ShipmentResponse->Response->ResponseStatus->Description == 'Success') {
                    $track= $result->ShipmentResponse->ShipmentResults->ShipmentIdentificationNumber;
                    $data = [
                        'tracking_number' => $track,
                        'waybill_url' => self::create_label($track,$result->ShipmentResponse->ShipmentResults->PackageResults->ShippingLabel->GraphicImage),
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];
                    // dd($data);
                    return $data;
                } else {
                    $data = [
                        'msg' => "Error from UPS " . json_encode($result),
                        'status' => 'error'
                    ];
                    return $data;
                }
            } else {
                $data = [
                    'msg' => "Error adding order to UPS",
                    'status' => 'error'
                ];
                return $data;
            }
        }

    }

    public static function create_label($track,$img){
        $image = base64_decode($img);
        $filename = 'ups-'.$track.'.png';
        file_put_contents(getcwd().'/ups_labels'. "/".$filename, $image);
        $file =url('/ups_labels'. "/".$filename);
        $path = getcwd().'/ups_labels/'.$filename;
        //image Rotation Function
        (new UPS)->RotateImage($file,$path);
        //image Rotation function ended
        //pdf generation code
        // sleep(15);
        $file1= (new UPS)->CreatePDF($path,$track,$filename);
        //pdf generation code ended
        return $file1;

    }

    public static function update_status($tracking,$id){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://onlinetools.ups.com/track/v1/details/'.$tracking,
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
        if($result->trackResponse->shipment[0]->package[0]->activity[0]->status->type=='D'){
//sleep(500);
            $order=order::find($id);
            $order->order_status='Delivered';
            $order->delivery_date=date("Y-m-d", strtotime($result->trackResponse->shipment[0]->package[0]->activity[0]->date));
            $order->Last_Status=$result->trackResponse->shipment[0]->package[0]->activity[0]->status->description;
            $order->save();
            Log::alert("deliverid UPS" . $id);
        }elseif($result->trackResponse->shipment[0]->package[0]->activity[0]->status->type=='RS'){
            $order=order::find($id);
            $newDate = date("Y-m-d", strtotime($result->trackResponse->shipment[0]->package[0]->activity[0]->date));
            //  $return_date=Carbon::now()->format('Y-m-d');
            $order->update(['return_date_carrier'=>$newDate]);
            Log::alert("Returned UPS" . $id);
        }else{
            $order=order::find($id);
            $order->order_status='inTransit';
            $order->Last_Status=$result->trackResponse->shipment[0]->package[0]->activity[0]->status->description;
            $order->save();
            Log::alert("inTransit UPS" . $id);
        }
    }

}
