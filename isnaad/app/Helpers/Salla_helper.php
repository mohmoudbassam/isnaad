<?php


namespace App\Helpers;


trait Salla_helper
{
    public function sendRequest($order)
    {
        if($order->shipping_number == $order->order_number){
            return 0;
        }

        if (strpos($order->order_number, 'cp') !== false) {
            return 0;
        }

        if(strlen($order->order_number)<= 4){
            //  exit();
            return 0;
            //dd(123);
        }
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
                        // "shipping_number" => $order->shipping_number,
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
                        //  "shipping_number" => $order->shipping_number
                    ];
                }
                $data = json_encode($data);
                //dd( $data );
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
//dd($data);
                $response = curl_exec($curl);
                // dd($response );
                $response = json_decode($response,true);
                // dd($response,$order->order_number);
                //  dd($response );
                try {

                }catch (Exception $exception){
                    Log::error('error salla no ' .  $order->order_number);
                    continue;
                }
                // dd( $response, $order->order_number);
                if(isset($response->status)){

                    return $response->status;
                }else{
                    return 0;
                }

            }
        }
    }

    private function getStatus($status)
    {
        if ($status == 'Delivered' || $status == 'delivered') {
            return 2;
        } elseif ($status == 'Returned' || $status == 'cancelled') {
            return 3;
        } else {
            return 1;
        }
    }

}
