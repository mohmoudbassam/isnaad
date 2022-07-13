<?php

namespace App\Http\Controllers\Notifcation;

use App\carrier;
use App\Http\Controllers\Controller;
use App\Notifications\delay_order;
use App\Notifications\delay_processing;
use App\order;
use App\proc_notifcation;
use App\slack_notifcation;
use App\store;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Illuminate\Support\Facades\Notification;


class SlackController extends Controller
{

    public function send_delay_order()
    {
        $dt = Carbon::now();
        $thisDay = $dt->toDateTimeString();
        $thisDay = $dt->format('Y-m-d');

        $orders = order::select([
            'id', 'shipping_number', 'order_number', 'shipping_date', 'tracking_number', 'carrier', 'store_id', 'city'
            , 'country'
        ])->whereRaw('DATEDIFF(' . "'$thisDay'" . ',shipping_date)>2')
            ->whereNotNull('shipping_date')
            ->where([['order_status', 'inTransit'], ['active', '1']])
            ->with(['store', 'carriers'])->get();
        $delayOrder = $orders->filter(function ($key, $value) {
            $dt = Carbon::now();

            if ($key->city == 'Riyadh') {
                return $key;
            } elseif ($key->city != 'Riyadh' && $key->country == 'SA') {

                if ($dt->diffInDays($key->shipping_date) > 4) {

                    return $key;
                }

            } elseif (($key->country != 'SA')) {
                if ($dt->diffInDays($key->shipping_date) > 10) {
                    return $key;
                }
            }
        });
        foreach ($delayOrder as $do) {
            $notify = slack_notifcation::where('order_id', $do->id)->first();

            if (!$notify) {
                slack_notifcation::create([
                    'notifcation_count' => 1,
                    'order_id' => $do->id
                ]);
                user::first()->slackChannel('delayOrder')->notify(new delay_order($do)) ;
            }

        }

    }

    public function send_delay_Processing(){
        $orders= order::where([['processing_status','=','1'],['active','1']])->orderBy('created_at')->get();
        foreach ($orders as $order){
            $current_date_time = Carbon::now();

            $notifcation=proc_notifcation::where('order_id',$order->id)->first();

            if($notifcation){

               if( $current_date_time->diffInDays($notifcation->created_at)>3 && $notifcation->notifcation_count==1){

                   user::first()->slackChannel('delayProcessing')->notify(new delay_processing($order,true)) ;
                    $notifcation->notifcation_count=$notifcation->notifcation_count+1;

                   $notifcation->save();
               }
            }elseif($current_date_time->diffInHours($order->created_at)> 24){
                user::first()->slackChannel('delayProcessing')->notify(new delay_processing($order,false)) ;

                proc_notifcation::create([
                    'notifcation_count' => 1,
                    'order_id' => $order->id
                ]);
            }

        }
    }

}

