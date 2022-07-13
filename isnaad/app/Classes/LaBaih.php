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
use App\Helpers\update_stores;

class LaBaih
{
    static $api_key = '159badd5b7e4acb8345d1172f2a0140eec2ceef5';
    static $base_url = 'https://partners.mylabaih.com/api/order/';

    public static function create_shipment($order)
    {
        $LaBaih = carrier::where('name', 'LaBaih')->first();
        $city = city::where('name', $order->custCity)->where('country_id', 1)->first();
        $carrier_city = carrier_city::where('city_id', $city->id)->where('carrier_id', 17)->first();
        if (!$carrier_city) {
            Log::error('city ID not found LaBaih for order');
            $data = [
                'msg' => "city ID not found LaBaih for order ",
                'status' => 'error'
            ];
            return $data;
        }
        $payment_method = ($order->CODamount > 0) ? 'COD' : 'PREPAID';
        $paymentAmount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $name = $order->custFName;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::$base_url . 'create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'api_key=' . self::$api_key . '&pickupDate=' . date("Y-m-d") . '&deliveryDate=' . date("Y-m-d") .
                '&customerOrderNo=' . $order->MLVID . '&noOfPieces=' . $order->Qty_Item . '&weightKgs=' . $order->WeightSum . '&dimensionsCm=' . '15' .
                '&itemDescription=' . $order->description_total . '&paymentMethod=' . $payment_method . '&paymentAmount=' . $paymentAmount . '&consigneeName=' . $name .
                '&consigneeMobile=' . $order->custPhone . '&consigneePhone=' . $order->custPhone . '&consigneeEmail=' . $order->from_mail . '&consigneeCity=' . $order->custCity .
                '&consigneeCommunity=' . $order->custAddress2 . '&consigneeAddress=' . $order->custAddress1 . '&consigneeFlatFloor=&consigneeLatLong=&consigneeSplInstructions=&store=' . $order->sender_name .
                '&shipperName=' . $order->sender_name . '&shipperMobile=' . '966537737764' . '&shipperEmail=' . $order->sender_email . '&shipperCity=' . 'riyadh' . '&shipperDistrict=' . 'riyadh' .
                '&shipperAddress=' . 'Al Mishael sulay Istanbul St.' . '&shipperLatLong=24.608642, 46.854488',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'cache-control: no-cache'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);
        //dd( $result);

        if ($result) {
            if ($result->message == "Success") {
                $data = [
                    'tracking_number' => $result->consignmentNo,
                    'waybill_url' => 'https://partners.mylabaih.com/api/order/printlabel-a6?consignmentNo=' . $result->consignmentNo . '&api_key=159badd5b7e4acb8345d1172f2a0140eec2ceef5',
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from LaBaih " . json_encode($result->message),
                    'status' => 'error'
                ];
                Log::alert("Error from LaBaih : " . $order->MLVID . ' ' . json_encode($result->message));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to LaBaih",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To LaBaih: " . $order->MLVID);
            return $data;
        }
    }

    public static function create_return_shipment($order)
    {
        $name = $order->fname . ' ' . $order->lname;
        $payment_type = "credit_balance";
        $payer = "sender";
        $charge_items = array();
        $charge_items[] = array(
            'charge_type' => 'service_custom',
            'charge' => 0,
            'payer' => 'recipient'
        );
        $data = [
            'sender_data' => [
                'address_type' => "business",
                'name' => $name,
                'email' => '',
                'apartment' => '',
                'building' => '',
                'street' => $order->address_1,
                'landmark' => $order->address_2,
                'city' => ['code' => 'riyadh'],
                'country' => ['id' => 191],
                'phone' => $order->phone
            ],
            'recipient_data' => [
                'address_type' => "business",
                'name' => 'isnaad',
                'email' => 'I.ansari@isnaad.sa',
                'apartment' => '',
                'building' => '',
                'street' => 'Istanbul St.' . ' ' . 'Al Mishael sulay',
                'landmark' => '',
                'city' => ['id' => 26148057],
                'country' => ['id' => 191],
                'phone' => '966537737764'
            ],
            'dimensions' => [
                'weight' => $order->weight,
                'width' => '10',
                'length' => '10',
                'height' => '10',
                'unit' => "METRIC",
                'domestic' => false
            ],
            'package_type' => [
                'courier_type' => 'EXPRESS_DELIVERY',
            ],
            'charge_items' => $charge_items,
            'recipient_not_available' => "do_not_deliver",
            'payment_type' => $payment_type,
            'payer' => $payer,
            'parcel_value' => '0',
            'fragile' => true,
            'note' => '',
            'piece_count' => '',
            'force_create' => true,
            'reference_id' => $order->order_number,
        ];
        $data = json_encode($data);
        //  dd($data);
        $result = self::send_request($data, 'v2/customer/order', 'POST');
        if ($result) {

            if ($result->status == "success") {
                $data = [
                    'tracking_number' => $result->data->order_number,
                    'waybill_url' => route('FDALabel', array('tracking_num' => $result->data->order_number)),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                return $data;
            } else {
                $data = [
                    'msg' => "Error from FDA " . json_encode($result->message),
                    'status' => 'error'
                ];
                Log::alert("Error from FDA : " . $order->order_number . ' ' . json_encode($result->message));
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to FDA",
                'status' => 'error'
            ];
            Log::alert("Error Adding Shipment To FDA: " . $order->order_number);
            return $data;
        }
    }

    public static function update_status($tracking_number, $id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://partners.mylabaih.com/api/order/get?api_key=159badd5b7e4acb8345d1172f2a0140eec2ceef5&consignmentNo=' . $tracking_number,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'cache-control: no-cache'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);
     //   dd($result);
        $array = [];
        if (isset($result->message)) {
            if ($result->message == "Success") {
                $or = order::find($id);
                if ($result->data[0]->status == "DELIVERED") {
                    $originalDate = $result->data[0]->actualDeliver;
                    $newDate = date("Y-m-d", strtotime($originalDate));
                   // $or = order::find($id);
                    $array=[
                        'order_status' => 'Delivered',
                        'delivery_date' => $newDate,
                        'Last_Status' => $result->data[0]->status
                    ];
                    Log::alert("deliverd LaBaih: " . $id);
                } elseif ($result->data[0]->status == 'returned_to_origin' || $result->data[0]->status == 'RTO DELIVERED') {
                    $return_date = Carbon::now()->format('Y-m-d');
                    if(!$or->return_date_carrier){
                     $array=[
                        ['return_date_carrier' => $return_date]
                    ];
                    }
                   $array=[
                      
                    ];
                    Log::alert("Returned LaBaih: " . $id);
                } else {
                    $array=['order_status' => 'inTransit', 'Last_Status' => $result->data[0]->status];
                    Log::alert("inTransit LaBaih: " . $id);
                }
                if($or->order_status!='Delivered' &&  $or->order_status!='Returned'){
                    $or->update($array);
                }

            } else {
                Log::error('error in order ' . $id);

            }
        }
    }

    private static function checkDate($date, $acount_id)
    {
        $firstDay = Carbon::now()->firstOfMonth();
        $middlMonth = $firstDay->addDay(15)->toDateString();
        $currentDate = Carbon::now()->format('Y-m-d');
        if ($acount_id == 29 || $acount_id == 13) {
            if ($currentDate >= $middlMonth) {
                if ($date < $middlMonth) {
                    return $middlMonth;
                } else {
                    return $date;
                }
            } else {
                return $date;
            }

        }
        $OrderMonth = Carbon::parse($date)->month;
        if ($OrderMonth == Carbon::now()->month) {
            return $date;
        }

        if ($OrderMonth != Carbon::now()->month) {
            $startDate = Carbon::now();
            $firstDay = $startDate->firstOfMonth();
            return $firstDay->toDateString();
        }

    }
}
