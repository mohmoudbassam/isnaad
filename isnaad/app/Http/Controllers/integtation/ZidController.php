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
use phpDocumentor\Reflection\Types\This;
use App\deliverd_orders;

class ZidController extends Controller
{

    public static $prod_base_url = "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler";


    public function update_status_Snackches()
    {
        Log::error('snackshes cron job began');
        $orders = order::where([['store_id', '10'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error snackshes cron job' . $order->id);
                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_robil()
    {
        Log::error('robil cron job began');
        $orders = order::where([['store_id', '11'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    //   dd($status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error robail cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_OverJoy()
    {
        Log::error('OverJoy cron job began');
        $orders = order::where([['store_id', '28'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    //dd($response);
                    if (isset($response->status)) {

                        if ($response->status == 'success' || $order->order_status == 'Returned') {

                            if ($order->order_status == 'Delivered') {
                                //  dd(1234);
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        // dd($response);
                        Log::error('error OverJoy cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Khaledbo()
    {
        Log::error('Khaledbo cron job began');
        $orders = order::where([['store_id', '21'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Khaledbo cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Manukahoney()
    {
        Log::error('Manukahoney cron job began');
        $orders = order::where([['store_id', '25'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));
                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Manukahoney cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Bayan()
    {
        Log::error('Bayan cron job began');
        $orders = order::where([['store_id', '26'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error bayan cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Anima()
    {
        Log::error('Anima cron job began');
        $orders = order::where([['store_id', '32'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error anima cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Boulevar()
    {
        Log::error('Boulevar cron job began');
        $orders = order::where([['store_id', '37'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Boulevar cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Tamraalqassim()
    {
        Log::error('Tamraalqassim cron job began');
        $orders = order::where([['store_id', '38'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Tamraalqassim cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_TandT()
    {
        Log::error('TandT cron job began');
        $orders = order::where([['store_id', '37'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
//dd($orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error T&T cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Nukhbataljawf()
    {
        Log::error('Nukhbataljawf cron job began');
        $orders = order::where([['store_id', '40'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Nukhbataljawf cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }
    public function update_status_RoseAndBee()
    {
        Log::error('RoseAndBee cron job began');
        $orders = order::where([['store_id', '41'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error RoseAndBee cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_Bindail()
    {
       
        Log::error('Bindail cron job began');
        $orders = order::where([['store_id', '45'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Bindail cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    public function update_status_DaadAbaya()
    {
        Log::error('DaadAbaya cron job began');
        $orders = order::where([['store_id', '50'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error DaadAbaya cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

        public function update_status_Qormuz()
    {

        //dd(115);
        Log::error('Qormuz cron job began');
        $orders = order::where([['store_id', '51'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Qormuz cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

        public function update_status_SignPerfumes()
    {

        //dd(3579);
        Log::error('SignPerfumes cron job began');
        $orders = order::where([['store_id', '55'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                    // dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error SignPerfumes cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }


        public function update_status_Seenglasses()
    {

       // dd(3579);
        Log::error('Seenglasses cron job began');
        $orders = order::where([['store_id', '56'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
       // dd( $orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                 //    dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Seenglasses cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

        public function update_status_Mrom()
    {

       // dd(3579);
        Log::error('Mrom cron job began');
        $orders = order::where([['store_id', '58'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
       // dd( $orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                 //    dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Seenglasses cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

        public function update_status_FRS()
    {

       // dd(3579);
        Log::error('FRS cron job began');
        $orders = order::where([['store_id', '65'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
       // dd( $orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                 //    dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error FRS cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

        public function update_status_Al_Semo()
    {

       // dd(3579);
        Log::error('Al-Semo cron job began');
        $orders = order::where([['store_id', '74'], ['processing_status', '0'], ['active', '1']])->doesnthave('deliverd_orders')->get();
       // dd( $orders);
        $carriers = \App\carrier::all();
        foreach ($orders as $order) {
            foreach ($carriers as $carrier) {
                if ($order->carrier == $carrier->name) {
                    $track_url = $carrier->tracking_link . $order->tracking_number;
                    $curl = curl_init();
                    $status = $this->getStatus($order->order_status);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.zid.sa/api/v1/logistics/operations/isnaad/eventHandler",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "{\r\n  \"action\": \"order-status-update\",\r\n  \"data\": {\r\n    \"order_number\": \"$order->order_number\",\r\n    \"order_status_code\": \"$status\",\r\n    \"tracking_number\": \"$order->tracking_number\",\r\n    \"tracking_url\": \"$track_url\",\r\n    \"tracking_details\": \"$carrier->name\"\r\n  }\r\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                        ),
                    ));

                    $response = curl_exec($curl);
                    $response = json_decode($response);
                 //    dd($response);
                    if (isset($response->status)) {
                        if ($response->status == 'success' || $order->order_status == 'Returned') {
                            if ($order->order_status == 'Delivered') {
                                deliverd_orders::create([
                                    'order_id' => $order->id
                                ]);

                            }
                        }
                    } else {
                        Log::error('error Al-Semo cron job' . $order->id);

                    }
                    curl_close($curl);
                }
            }
        }
    }

    private function getStatus($status)
    {
        if ($status == 'Delivered') {
            return "2";
        } elseif ($status == 'Returned') {
            return "3";
        } else {
            return "1";
        }
    }


}
