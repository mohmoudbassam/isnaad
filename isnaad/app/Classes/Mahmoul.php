<?php

namespace App\Classes;

use App\carrier;
use App\carrier_city;
use App\city;
use App\order;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Mahmoul
{

    static $test_base_url = 'https://app.shipsy.in/api/customer/integration/consignment/';
    static $prod_base_url = 'https://til.sa/api/v2/';
    static $wblurl = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/shippinglabel/link?reference_number=';
    static $track_url = 'track?reference_number=';
    static $Test_API_KEY = '5251a07823daab3df2e632e866dedd';
    //   5251a07823daab3df2e632e866dedd
    static $Prod_API_KEY = 'TMX247563ARTYU78XC87QW1236T39487';
    static $CustomerCode = "CUS37";

    public static function send_request($data, $end_point, $request_type)
    {
        $header = array('Content-Type' => 'application/json', 'API-KEY' => self::$Test_API_KEY);

        $client = new GuzzleHttpClient();
        $res = '';
        try {
            if ($request_type == 'POST') {
                $res = $client->$request_type(self::$test_base_url . $end_point, [
                    'headers' => $header,
                    'body' => $data
                ]);
                return json_decode($res->getBody()->getContents());
            } else {
                $res = $client->$request_type(self::$test_base_url . $end_point, [
                    'headers' => ['Content-Type' => 'application/json'
                        , 'API-KEY' => self::$Test_API_KEY],
                    //  'body' => $data
                ]);
                return json_decode($res->getBody()->getContents());
            }
        } catch (\Exception $exception) {
            Log::error('error in send request for mahmoul ' . $exception->getMessage());
            return 0;
        }
    }

    public static function create_shipment($order)
    {
        //dd($order->MLVID);
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $paymode = ($cod_amount == 0) ? '' : 'Cash';
        $data = [
            'consignments' => [
                [
                    'customer_code' => self::$CustomerCode,
                    'reference_number' => '',
                    'service_type_id' => 'Dry',
                    'load_type' => 'NON-DOCUMENT',
                    'description' => $order->description_total,
                    'cod_favor_of' => '',
                    'dimension_unit' => 'cm',
                    'length' => '10',
                    'width' => '10',
                    'height' => '10',
                    'weight_unit' => 'kg',
                    'weight' => $order->WeightSum,
                    'declared_value' => $order->declared_total,
                    'declared_price' => '',
                    'cod_amount' => $cod_amount,
                    'cod_collection_mode' => $paymode,
                    'prepaid_amount' => '',
                    'num_pieces' => '1',
                    'customer_reference_number' => '',
                    'is_risk_surcharge_applicable' => true,
                    'origin_details' => [
                        "name" => $order->sender_name,
                        'phone' => '966537737764',
                        'alternate_phone' => '',
                        'address_line_1' => 'Al Mishael sulay',
                        'address_line_2' => 'Istanbul St.',
                        'city' => 'riyadh',
                        'state' => 'riyadh'
                    ],
                    'destination_details' => [
                        'name' => $order->custFName . ' ' . $order->custLName,
                        'phone' => $order->custPhone,
                        'alternate_phone' => '',
                        'address_line_1' => $order->custAddress1,
                        'address_line_2' => $order->custAddress2,
                        'city' => $order->custCity,
                        'state' => $order->custState
                    ],
                    'pieces_detail' => [
                        'description' => $order->description_total,
                        'declared_value' => $order->declared_total,
                        'weight' => $order->WeightSum,
                        'height' => $order->Height,
                        'length' => $order->Length,
                        'width' => $order->Width
                    ],
                ]
            ]
        ];
        $data = json_encode($data);
        $result = self::send_request($data, 'softdata', 'POST');
       // dd($order->MLVID);
        if ($result) {
            if ($result->status == 'OK') {
                $data = [
                    'tracking_number' => $result->data[0]->reference_number,
                    'waybill_url' => self::create_label($result->data[0]->reference_number),
                    'status' => 'success',
                    'msg' => 'shipment created successfully'
                ];
                // dd($data);
                return $data;
            } else {
                $data = [
                    'msg' => "Error from Mahmoul " . json_encode($result->data),
                    'status' => 'error'
                ];
                return $data;
            }
        } else {
            $data = [
                'msg' => "Error adding order to Mahmoul",
                'status' => 'error'
            ];
            return $data;
        }
    }

    public static function create_label($referance_number)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://app.shipsy.in/api/customer/integration/consignment/shippinglabel/link?reference_number=" . $referance_number . "&is_small=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "api-key: 5251a07823daab3df2e632e866dedd",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        $url = substr($response->data->url, 0, -13);

        //  dd($url);
        return $url;
    }

    public static function update_status($tracking_number, $id)
    {
        // dd('asdasd');
        $result = self::send_request([], 'track?reference_number=' . $tracking_number, 'GET');
        //dd($result);
        // $result = json_decode($result);

        if (isset($result->status)) {
            if ($result->status == 'delivered') {

                $order = order::find($id);
                $order->order_status='Delivered';
                $date = $result->events[0]->event_time;
                $newdate = date("Y-m-d", substr($date, 0, 10));
                //   dd($newdate);
                $order->delivery_date = $newdate;
                Log::alert("deliverd mahmoul : " . $id);
                $order->Last_Status = $result->status;
                $order->save();
            } //


            elseif ($result->status == 'rto') {
                $order = order::find($id);
              //  $order->order_status = 'Returned';
                $order->Last_Status = $result->status;
                $order->save();
            } else {
                $order = order::find($id);
                $order->order_status = 'inTransit';
                $order->Last_Status = $result->status;
                $order->save();
            }
        } else {

            Log::error('Mahmoul issue order id: ' . $id);
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

}

