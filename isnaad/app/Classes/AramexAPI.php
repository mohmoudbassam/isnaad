<?php

namespace App\Classes;


use App\order;
use Illuminate\Support\Facades\Log;
use SoapClient;
use App\order_status;
use App\Helpers\update_stores;
use Carbon\Carbon;

class AramexAPI extends update_stores
{


    static $live_wsdl = 'https://ws.aramex.net/shippingapi.v2/shipping/service_1_0.svc?wsdl';
    static $test_wsdl = 'https://ws.aramex.net/shippingapi.v2/shipping/service_1_0.svc?wsdl';
    static $live_tracking = 'http://ws.aramex.net/shippingapi/tracking/service_1_0.svc?wsdl';
    static $test_tracking = 'http://ws.dev.aramex.net/shippingapi/tracking/service_1_0.svc?wsdl';
    static $AccountCountryCode = "SA";
    static $AccountEntity = "RUH";

    static $AccountNumber = "60500947";
    static $AccountPin = "442543";
    static $UserName = "i.ansari@isnaad.sa";
    static $Password = "Isnaad12344!";

    static $Version = "v1.0";
    static $Address1 = "testAddress";
    static $city = "Riyadh";

    //////////////////////////////////
/*
     static $AccountNumber = "60508612";
     static $AccountPin = "331432";
     static $UserName = "support@isnaad.sa";
     static $Password = "Isnaad12344!";
     */

    public static function create_shipment($order)
    {
        // Aramex::createShipments()->addShipment()
        $City = self::$city;
        $Weightunit = "KG";
        $mode = 0;
        $name = $order->custFName;
        $company = $order->sender_name;
        $phone = '';
        $mobile = '966537737764';
        $email = $order->sender_email;
        $location = 'riyadh_isnaad werhouse';

        if ($mode == 1)
            $fold = "aramex/live/";
        else {
            $fold = "aramex/test/";
        }
        $i = 0;
        $tot_qty = 2;
        $tot_weight = $order->WeightSum;

        //  $country_code="kw";
        $NumberOfPieces = 1;
        if (isset($order->numberOfBoxInternatonal)){

        $NumberOfPieces=$order->numberOfBoxInternatonal;

        }
        elseif(isset($order->isDevide)){
        $NumberOfPieces =$order->newQty;
        }
        //dd($NumberOfPieces);
        $params = array(
            'Shipments' => array(
                'Shipment' => array(
                    'Shipper' => array(
                        'Reference1' => $order->MLVID,
                        'Reference2' => $order->orderNum,
                        'AccountNumber' => self::$AccountNumber,
                        'PartyAddress' => array(
                            'Line1' => 'Al Mishael sulay',
                            'Line2' => '',
                            'Line3' => '',
                            'City' => 'riyadh',
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => self::$AccountCountryCode
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => 'Irshad Ansari',
                            'Title' => '',
                            'CompanyName' => $company,
                            'PhoneNumber1' => $mobile,
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $mobile,
                            'EmailAddress' => $email,
                            'Type' => ''
                        ),
                    ),

                    'Consignee' => array(
                        'Reference1' => $order->MLVID,
                        'Reference2' => $order->orderNum,
                        'AccountNumber' => self::$AccountNumber,
                        'PartyAddress' => array(
                            'Line1' => $order->custAddress1,
                            'Line2' => $order->custAddress2,
                            'Line3' => '',
                            'City' => $order->custCity,
                            'StateOrProvinceCode' => $order->custState,
                            'PostCode' => $order->custZip,
                            'CountryCode' => $order->custCountry
                        ),

                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $name,
                            'Title' => '',
                            'CompanyName' => '_',
                            'PhoneNumber1' => $order->custPhone,
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' =>$order->custPhone,
                            'EmailAddress' => ($order->custEmail) ? $order->custEmail : 'test@test.com',
                            'Type' => ''
                        ),
                    ),

                    'Reference1' => $order->MLVID,
                    'Reference2' => '',
                    'Reference3' => '',
                    'ForeignHAWB' => '',
                    'TransportType' => 0,
                    'ShippingDateTime' => time(),
                    'DueDate' => time(),
                    'PickupLocation' => $location,
                    'PickupGUID' => '',
                    'Comments' => '',

                    'Details' => array(
                        'Dimensions' => array(
                            'Length' => '15',
                            'Width' =>'15',
                            'Height' => '15',
                            'Unit' => "cm",

                        ),

                        'ActualWeight' => array(
                            'Value' => $order->WeightSum,
                            'Unit' => $Weightunit
                        ),

                        'ProductGroup' => ($order->custCountry == 'SA') ? "DOM" : "EXP",
                        'ProductType' => ($order->custCountry == 'SA') ? "CDS" : "PPX",
                        'PaymentType' => 'P',
                        'PaymentOptions' => '',
                        'Services' => ($order->CODamount > 0) ? "CODS" : "",
                        'NumberOfPieces' => $NumberOfPieces,
                        'DescriptionOfGoods' => $order->All_Sku,
                        'GoodsOriginCountry' => self::$AccountCountryCode,

                        'CashOnDeliveryAmount' => array(
                            'Value' => ($order->CODamount > 0) ? $order->CODamount : 0,
                            'CurrencyCode' => ($order->custCountry == 'SA') ? "SAR" : "USD"
                        ),

                        'InsuranceAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CollectAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CashAdditionalAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CashAdditionalAmountDescription' => '',

                        'CustomsValueAmount' => array(
                            'Value' => ($order->custCountry == 'SA') ? "0" : "1",
                            'CurrencyCode' => 'USD'
                        ),

                        'Items' => array()
                    ),
                ),
            ),

            'ClientInfo' => array(
                'AccountCountryCode' => self::$AccountCountryCode,
                'AccountEntity' => self::$AccountEntity,
                'AccountNumber' => self::$AccountNumber,
                'AccountPin' => self::$AccountPin,
                'UserName' => self::$UserName,
                'Password' => self::$Password,
                'Version' => self::$Version
            ),
            'LabelInfo' => array(
                'ReportID' => 9729,
                'ReportType' => 'URL',
            ),
            'ShipmentItem' => array(
                'PackageType' => 'Box',
                //'Quantity' => '5',
                'Quantity' => $tot_qty,
                'Weight' => array(
                    'Value' => $order->WeightSum,
                    'Unit' => 'Kg',
                ),
                'Comments' => $order->All_Sku,
                'Reference' => ''
            ),
        );
        // dd($params);
        try {
            $result = self::send_request($params, 'CreateShipments');
             //dd(1);
            if ($result) {
                if ($result->HasErrors != 'true') {
                    $data = [
                        'tracking_number' => $result->Shipments->ProcessedShipment->ID,
                        'waybill_url' => route('AramexLabel', array('tracking_num' => $result->Shipments->ProcessedShipment->ID)),
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];

                } else {
                    $data = [
                        'tracking_number' => '',
                        'waybill_url' => '',
                        'status' => 'error',
                        'msg' => json_encode($result->Shipments->ProcessedShipment->Notifications->Notification)
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


        } catch (\Exception $fault) {
            $data = [
                'msg' => "Error from Aramex " . $fault->getMessage(),
                'status' => 'error'
            ];
            Log::error("Error from Aramex : " . $order->MLVID . ' ' . $fault->getMessage());
            return 0;
        }
        return ($data);
    }

    public static function create_return_shipment($order)
    {
        // Aramex::createShipments()->addShipment()
        $Weightunit = "KG";
        $mode = 0;
        $name = $order->fname . ' ' . $order->lname;
        $location = $order->address_1 . ' ' . $order->address_1;
        $phone = '966537737764';
        if ($mode == 1)
            $fold = "aramex/live/";
        else {
            $fold = "aramex/test/";
        }
        $tot_qty = 1;

        $params = array(
            'Shipments' => array(
                'Shipment' => array(
                    'Shipper' => array(
                        'Reference1' => $order->shipping_number,
                        'Reference2' => $order->order_number,
                        'AccountNumber' => self::$AccountNumber,
                        'PartyAddress' => array(
                            'Line1' => $order->address_1,
                            'Line2' => $order->address_2,
                            'Line3' => '',
                            'City' => $order->city,
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => $order->country
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $name,
                            'Title' => '',
                            'CompanyName' => $name,
                            'PhoneNumber1' => $order->phone,
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $order->phone,
                            'EmailAddress' => '',
                            'Type' => ''
                        ),
                    ),

                    'Consignee' => array(
                        'Reference1' => $order->shipping_number,
                        'Reference2' => $order->order_number,
                        'AccountNumber' => self::$AccountNumber,
                        'PartyAddress' => array(
                            'Line1' => 'Al Mishael sulay',
                            'Line2' => 'Istanbul St.',
                            'Line3' => '',
                            'City' => 'Riyadh',
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => self::$AccountCountryCode
                        ),

                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => 'Irshad Ansari',
                            'Title' => '',
                            'CompanyName' => 'Isnaad',
                            'PhoneNumber1' => $phone,
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $phone,
                            'EmailAddress' => 'I.ansari@isnaad.sa',
                            'Type' => ''
                        ),
                    ),

                    'Reference1' => $order->order_number,
                    'Reference2' => '',
                    'Reference3' => '',
                    'ForeignHAWB' => '',
                    'TransportType' => 0,
                    'ShippingDateTime' => time(),
                    'DueDate' => time(),
                    'PickupLocation' => $location,
                    'PickupGUID' => '',
                    'Comments' => '',

                    'Details' => array(
                        'Dimensions' => array(
                            'Length' => '10',
                            'Width' => '10',
                            'Height' => '10',
                            'Unit' => "cm",

                        ),

                        'ActualWeight' => array(
                            'Value' => $order->weight,
                            'Unit' => $Weightunit
                        ),

                        'ProductGroup' => 'DOM',//($order->country == 'SA') ? "DOM" : "EXP",
                        'ProductType' => 'PPX',//($order->country == 'SA') ? "CDS" : "PPX",
                        'PaymentType' => 'P',
                        'PaymentOptions' => '',
                        'Services' => "",
                        'NumberOfPieces' => '1',
                        'DescriptionOfGoods' => $order->description,
                        'GoodsOriginCountry' => self::$AccountCountryCode,

                        'CashOnDeliveryAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => 'SAR'
                        ),

                        'InsuranceAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CollectAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CashAdditionalAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CashAdditionalAmountDescription' => '',

                        'CustomsValueAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => 'SAR'
                        ),

                        'Items' => array()
                    ),
                ),
            ),

            'ClientInfo' => array(
                'AccountCountryCode' => self::$AccountCountryCode,
                'AccountEntity' => self::$AccountEntity,
                'AccountNumber' => self::$AccountNumber,
                'AccountPin' => self::$AccountPin,
                'UserName' => self::$UserName,
                'Password' => self::$Password,
                'Version' => self::$Version
            ),
            'LabelInfo' => array(
                'ReportID' => 9729,
                'ReportType' => 'URL',
            ),
            'ShipmentItem' => array(
                'PackageType' => 'Box',
                'Quantity' => $order->Qty_Item,
                'Weight' => array(
                    'Value' => $order->weight,
                    'Unit' => 'Kg',
                ),
                'Comments' => '',
                'Reference' => ''
            ),
        );
        //dd($params);
        try {
            $result = self::send_request($params, 'CreateShipments');
            dd($result);
            if ($result) {
                if ($result->HasErrors != 'true') {
                    $data = [
                        'tracking_number' => $result->Shipments->ProcessedShipment->ID,
                        'waybill_url' => route('AramexLabel', array('tracking_num' => $result->Shipments->ProcessedShipment->ID)),
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];

                } else {
                    $data = [
                        'tracking_number' => '',
                        'waybill_url' => '',
                        'status' => 'error',
                        'msg' => json_encode($result->Shipments->ProcessedShipment->Notifications->Notification)
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


        } catch (\Exception $fault) {
            $data = [
                'msg' => "Error from Aramex " . $fault->getMessage(),
                'status' => 'error'
            ];
            Log::error("Error from Aramex : " . $order->shipping_number . ' ' . $fault->getMessage());
            return 0;
        }
        return ($data);
    }

    public static function send_request($data, $end_point)
    {
        try {
            $soapClient = new SoapClient(self::$test_wsdl);
            $auth_call = $soapClient->$end_point($data);
            return $auth_call;
        } catch (SoapFault $fault) {
            Log::alert("Error from Aramex : " . $fault->getMessage());
            return '';
        }

    }

    public static function create_label($tracking_num)
    {
        $params = array(
            'ClientInfo' => array(
                'AccountCountryCode' => self::$AccountCountryCode,
                'AccountEntity' => self::$AccountEntity,
                'AccountNumber' => self::$AccountNumber,
                'AccountPin' => self::$AccountPin,
                'UserName' => self::$UserName,
                'Password' => self::$Password,
                'Version' => self::$Version
            ),
            'ShipmentNumber' => $tracking_num,
            'LabelInfo' => array(
                'ReportID' => 9729,
                'ReportType' => 'URL',
            ),
        );

        try {
            $result = self::send_request($params, 'PrintLabel');
            // dd($result);
            return $result->ShipmentLabel->LabelURL;

        } catch (\Exception $e) {

        }
    }

    public static function update_status($tracking_num, $id)
    {

        // $update_stores = new update_stores();
        $soapClient = new SoapClient(self::$live_tracking);
//dd( $soapClient);
//$tracking_num=45762223706;
        /*
            parameters needed for the trackShipments method , client info, Transaction, and Shipments' Numbers.
            Note: Shipments array can be more than one shipment.
        */
        $params = array(
            'ClientInfo' => array(
                'AccountCountryCode' => self::$AccountCountryCode,
                'AccountEntity' => self::$AccountEntity,
                'AccountNumber' => self::$AccountNumber,
                'AccountPin' => self::$AccountPin,
                'UserName' => self::$UserName,
                'Password' => self::$Password,
                'Version' => self::$Version
            ),


            'Shipments' => array(
                $tracking_num
            ),
            'GetLastTrackingUpdateOnly' => 1
        );

        try {

            try {

                $auth_call = $soapClient->TrackShipments($params);
                $stauts = order_status::where([['isnaad_staus', 'Delivered'], ['carrier', 'Aramex']])->select('carrier_status')->get()->groupBy('carrier_status')->toArray();

            } catch (\Exception $e) {
                Log::alert("error from aramex api" . $id .$e);
                return 0;
            }

            if (isset($auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY)) {
                if (isset($auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value)) {

                    //dd($auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription);

                    if (array_key_exists($auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateCode, $stauts)) {

                        $originalDate = $auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDateTime;

                        $newDate = date("Y-m-d", strtotime($originalDate));
                        $order = order::find($id);
                        $order->update([
                            'order_status' => 'Delivered'
                            , 'delivery_date' =>$newDate,
                            'Last_Status' => $auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription
                        ]);

                        $order->save();
                        Log::alert("deliverid aramex" . $id);
                    } elseif ($auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateCode == 'SH069') {
                        $order = order::find($id);
                        $originalDate = $auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDateTime;
                        $newDate = date("Y-m-d", strtotime($originalDate));
                        //  $return_date=Carbon::now()->format('Y-m-d');
                        $order->update(['return_date_carrier'=> $originalDate]);
                        Log::alert("return aramex" . $id);


                    } else {

                        $order = order::find($id);
                        if($order->order_status !='Delivered' && $order->order_status!='Returned'){
                            $order->update(['order_status' => 'inTransit', 'Last_Status' => $auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription]);
                            $order->save();
                            Log::alert("intransit aramex" . $id);
                        }

                    }
                }


            } else {
                $order = order::find($id)->update(['order_status' => 'inTransit']);
            }
        } catch (SoapFault $fault) {

            die('Error : ' . $fault->faultstring);
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
    /*
        private static function checkDate($date)
        {
            $OrderMonth = Carbon::parse($date)->month;
            if ($OrderMonth == Carbon::now()->month) {
                return $date;
            }

            if ($OrderMonth != Carbon::now()->month) {
                $startDate = Carbon::now();
                $firstDay = $startDate->firstOfMonth();
                return $firstDay->toDateString();
            }

        }*/
}
