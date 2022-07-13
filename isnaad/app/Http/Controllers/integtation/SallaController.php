<?php

namespace App\Http\Controllers\integtation;

use App\carrier;
use App\Http\Controllers\Controller;
use App\Models\in_transit;
use App\deliverd_orders;
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

class SallaController extends Controller
{

    public static $url = "https://s.salla.sa/api/webhook/isnaad/";

    public static function send_request($data, $end_point, $request_type)
    {

        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json');

        $client = new GuzzleHttpClient();

        $res = '';
        try {
            if ($request_type == 'POST') {
                $res = $client->$request_type(self::$url . $end_point, [
                    'headers' => $header,
                    'body' => $data
                ]);
                return json_decode($res->getBody()->getContents());
            } else {

                $res = $client->$request_type(self::$url . $end_point, [
                    'headers' => ['Content-Type' => 'application/json'
                        , 'api-key' => self::$Prod_API_KEY],
                    //'body' => $data
                ]);

                return json_decode($res->getBody()->getContents());
            }
        } catch (\Exception $exception) {
            Log::error('error in send request for salla ' . $exception->getMessage());
            return 0;
        }
    }


    public function update_status_Sadatalbukhur()
    {

        $orders = order::where([['store_id', '5'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_JAWANI()
    {

        $orders = order::where([['store_id', '9'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_BEEJABA()
    {

        $orders = order::where([['store_id', '16'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_wixana()
    {

        $orders = order::where([['store_id', '33'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                     Log::error('suc wixana orNum' . $order->order_number);
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Sorrah()
    {

        $orders = order::where([['store_id', '13'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();

        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {

                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Rosemond()
    {

        $orders = order::where([['store_id', '19'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();

        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Rahig()
    {

        $orders = order::where([['store_id', '20'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Folicello()
    {

        $orders = order::where([['store_id', '15'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Bedro()
    {

        $orders = order::where([['store_id', '23'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Sidra_Oil()
    {

        $orders = order::where([['store_id', '29'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
     
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        //dd(123);
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Kamamy()
    {

        $orders = order::where([['store_id', '30'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Coffee_secrets()
    {

        $orders = order::where([['store_id', '31'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_Golden_Occasion()
    {

        $orders = order::where([['store_id', '27'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                 $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }

    public function update_status_wareedmedical()
    {

        $orders = order::where([['store_id', '35'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }


    public function update_status_saif_nakhla()
    {

        $orders = order::where([['store_id', '36'], ['processing_status', '0'], ['active', '1']])
            ->doesnthave('deliverd_orders')->get();
           
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            if ($order->order_status == 'inTransit' || $order->order_status == 'Data Uplouded') {

                if ($order->in_transit != null) {
                    continue;
                } else {

                    $statusCode = $this->sendRequest($order);
                    if ($statusCode == 200) {
                        in_transit::create([
                            'order_id' => $order->id
                        ]);
                    }
                }
            } elseif ($order->order_status == 'Delivered' || $order->order_status == 'Returned') {
                  $statusCode = $this->sendRequest($order);
                if ($statusCode == 200) {
                    deliverd_orders::create([
                        'order_id' => $order->id
                    ]);
                }
            }

        }
    }
    private function getStatus($status)
    {
        if ($status == 'Delivered' || $status == 'delivered') {
            return 2;
        } elseif ($status == 'Returned') {
            return 3;
        } else {
            return 1;
        }
    }

    public function sendRequest($order)
    {
        $carriers = \App\carrier::all();
        foreach ($carriers as $carrier) {
            if ($order->carrier == $carrier->name) {
                $status = $this->getStatus($order->order_status);
                $tracking_link = $carrier->tracking_link . $order->tracking_number;
                $curl = curl_init();
                $status = (int)$this->getStatus($order->order_status);
                $trLink = 'http://portal.isnaad.sa/' . $order->order_number;
                if ($status == 1) {

                    $data = [
                        "auth-token" => "IrwpV6OTf6FT2ASfc5mBct6EGBl",
                        "order_id" => $order->order_number,
                        "status" => $status,
                        "tracking_url" => $carrier->tracking_link . $order->tracking_number,
                        "tracking_number" => $order->tracking_number,
                        "with_notification" => true,

                        "note" => "شركة الشحن :$carrier->name
                             رابط التتبع:$trLink
                            "

                    ];
                } else {
                    $data = [
                        "auth-token" => "IrwpV6OTf6FT2ASfc5mBct6EGBl",
                        "order_id" => $order->order_number,
                        "status" => $status,
                        "tracking_url" => $carrier->tracking_link . $order->tracking_number,
                        "tracking_number" => $order->tracking_number,
                    ];
                }
                $data = json_encode($data);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://s.salla.sa/api/webhook/isnaad/order/" . $order->order_number,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                    ),
                ));

                $response = curl_exec($curl);
                $response = json_decode($response);
                   // dd($response,$order->order_number);
              //  dd( $response);
                 try {
            
        }catch (Exception $exception){
               Log::error('er wixana orNum' .  $order->order_number);
            continue;
        }
                return $response->status;
            }
        }
    }


}
