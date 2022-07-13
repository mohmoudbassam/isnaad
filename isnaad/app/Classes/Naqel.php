<?php

namespace App\Classes;


use App\carrier;
use App\city;
use App\city_name;
use App\carrier_city;
use App\Barq_order_id;
use App\Models\naqel_city;
use App\order;
use App\store;
use http\Env\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Redirect;
use PDF;
use App\Helpers\update_stores;
use Carbon\Carbon;
use SoapClient;
use SimpleXMLElement;
class Naqel
{
    static $ClientID = '9021164';
    static $password = '90@As!e4';
    static $base_url = 'https://infotrack.naqelexpress.com/NaqelAPIServices/NaqelAPI/9.0/XMLShippingService.asmx?WSDL';
                        
    


    public static function send_request($data, $endpoint)
    {
        $soapClient = new SoapClient(self::$base_url);
        $result = $soapClient->$endpoint($data);
        return $result;
    }

    public static function create_shipment($order)
    {
        $naqel_city= naqel_city::where([['CityName', $order->custCity]])->first();
      
        if($naqel_city){
           $city= $naqel_city;
        }else{
            $city= false;
        }

        if($city){
            $parms = [

                '_ManifestShipmentDetails' => array(
                    'ClientInfo' => array(
                        'ClientAddress' => array(
                            'PhoneNumber' => '966537737764',
                            'POBox' => '',
                            'ZipCode' => '',
                            'Fax' => '',
                            'FirstAddress' => 'Al Mishael sulay' . ' ' . 'Istanbul St.',
                            'Location' => '',
                            'CountryCode' => 'KSA',
                            'CityCode' => 'RUH',


                        ),
                        'ClientContact' => array(
                            'Name' => $order->sender_name,
                            'Email' => $order->sender_email,
                            'PhoneNumber' => '966537737764',
                            'MobileNo' => ''
                        ),
                        'ClientID' => self::$ClientID,
                        'Password' => '90@As!e4',
                        'Version' => '9.0'
                    ),
                    'ConsigneeInfo' => array(
                      'ConsigneeNationalID' => 0.0,
                        'ConsigneeName' => $order->custFName . ' ' . $order->custLName,
                        'Email' => '',
                        'Mobile' => '',
                        'PhoneNumber' => $order->custPhone,
                        'Fax' => '',
                        'District' => '',
                        'Address' => $order->custAddress1,
                        'NationalAddress' => '',
                        'Near' => '',
                        'CountryCode' => $city->CountryCode,//from table ,
                        'CityCode' => $city->CityCode//from table,
                    ),
                    '_CommercialInvoice' => array(
                        'RefNo' => $order->MLVID,
                        'InvoiceNo' => "".$order->MLVID,
                        'InvoiceDate' => carbon::Now()->format('Y-m-d-H:i'),//today date
                        'Consignee' => $order->custFName . ' ' . $order->custLName,
                        'ConsigneeAddress' => $order->custAddress1,
                        'ConsigneeEmail' => '',
                        'MobileNo' => '',
                        'Phone' => '',
                        'TotalCost' => $order->declared_total,
                        'CurrencyCode' => 'SAR',
                        
                    ),
                    'CurrenyID' => 1,
                    'BillingType' => ($order->CODamount > 0) ? 5 : 1,//bayed 1 cod 5
                    'PicesCount' => 1,
                    'Weight' => $order->WeightSum,
                    'DeliveryInstruction' => '',
                    'CODCharge' => ($order->CODamount > 0) ? $order->CODamount : 0 ,//cod_amount if BillingType 5
                    'CreateBooking' => false,
                    'isRTO' => false,
                    'GeneratePiecesBarCodes' => false,
                    'PromisedDeliveryDateFrom' => carbon::Now()->format('Y-m-d-H:i'),//today date
                    'PromisedDeliveryDateTo' => Carbon::now()->addDays(2)->format('Y-m-d-H:i'),//+2
                    'LoadTypeID' => 36,
                    'DeclareValue' => $order->declared_total,
                    'GoodDesc' => 'SKU',
                    'Latitude' => $order->description_total,
                    'Longitude' => '',
                    'RefNo' => $order->MLVID.'123',
                    'Width' => 22.00,
                    'Length' => 29.00,
                    'Height' => 10.50,
                    'VolumetricWeight' => 2,
                    'InsuredValue' => 0,
                    'Reference1' => '',
                    'Reference2' => '',
                    'GoodsVATAmount' => 0,
                    'IsCustomDutyPayByConsignee' => false,
                    'PickUpPoint' => ''


                )
            ];
            $result = self::send_request($parms, 'CreateWaybill');
            
           
                if ($result) {
                    if ($result->CreateWaybillResult->HasError != true) {
                        $data = [
                            'tracking_number' => $result->CreateWaybillResult->WaybillNo,
                            'waybill_url' => route('NAqel_Label', array('tracking_num' => $result->CreateWaybillResult->WaybillNo)),
                            'status' => 'success',
                            'msg' => 'shipment created successfully'
                        ];

                    } else {
                        $data = [
                            'tracking_number' => '',
                            'waybill_url' => '',
                            'status' => 'error',
                            'msg' => '',
                        ];

                    }
                } else {
                    $data = [
                        'tracking_number' => '',
                        'waybill_url' => '',
                        'status' => 'error',
                        'msg' => 'Error adding order to Aramex'
                    ];

                }
            
            return ($data);
        }else{
            return false;
        }




    }

    public static function create_label($tracking_num)
    {
        $params = [
            'clientInfo' => array(
                'PhoneNumber' => '',
                'POBox' => '',
                'ZipCode' => '',
                'Fax' => '',
                'FirstAddress' => '',
                'Location' => '',
                'CountryCode' => '',
                'CityCode' => '',
                'ClientContact' => array(
                    'Name' => '',
                    'Email' => '',
                    'PhoneNumber' => '',
                    'MobileNo' => '',
                ),
                'ClientID' => self::$ClientID,
                'Password' => self::$password,
                'Version' => '9.0'
            ),
            'WaybillNo' => $tracking_num,
            'StickerSize' => 'FourMEightInches'

        ];
        $result = self::send_request($params, 'GetWaybillSticker');
        return $result->GetWaybillStickerResult;
    }


    public static function update_status($tracking_num,$id)
    {
       // dd($tracking_num);
  $params = [
             'ClientInfo' => array(
                        'ClientAddress' => array(
                            'PhoneNumber' => '966537737764',
                            'POBox' => '',
                            'ZipCode' => '',
                            'Fax' => '',
                            'FirstAddress' => 'Al Mishael sulay' . ' ' . 'Istanbul St.',
                            'Location' => '',
                            'CountryCode' => 'KSA',
                            'CityCode' => 'RUH',


                        ),
                        'ClientContact' => array(
                            'Name' => '',
                            'Email' =>'',
                            'PhoneNumber' => '966537737764',
                            'MobileNo' => ''
                        ),
                       
            
                 'ClientID' => self::$ClientID,
                        'Password' => '90@As!e4',
                        'Version' => '9.0'
            ),
            'WaybillNo' => $tracking_num,
          
        ];
        $result = self::send_request($params, 'TraceByWaybillNo');
         if(isset($result->TraceByWaybillNoResult->Tracking)){
       $countOfTraking= count($result->TraceByWaybillNoResult->Tracking);
        $lastEventCode=$result->TraceByWaybillNoResult->Tracking[$countOfTraking-1]->EventCode;
            if ($lastEventCode == 7) {
                $order = order::find($id);
                $order->order_status = 'Delivered';
                $newdate= date("Y-m-d", strtotime($result->TraceByWaybillNoResult->Tracking[$countOfTraking-1]->Date));
               // dd($newdate);
                $order->delivery_date = $newdate;
                $order->Last_Status = $result->TraceByWaybillNoResult->Tracking[$countOfTraking-1]->Activity;
                $order->save();
            } elseif ($lastEventCode == 9 || $lastEventCode == 221) {
                $order = order::find($id);
                $order->return_date_carrier = date("Y-m-d", $result->TraceByWaybillNoResult->Tracking[$countOfTraking-1]->Date);
                $order->Last_Status = $result->TraceByWaybillNoResult->Tracking[$countOfTraking-1]->Activity;
                $order->save();
            } else {
                $order = order::find($id);
                $order->Last_Status = $result->TraceByWaybillNoResult->Tracking[$countOfTraking-1]->Activity;
                $order->save();
            }
         }
//dd($oXML->EventCode);
    }



}
