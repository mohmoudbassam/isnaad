<?php

namespace App\Http\Controllers\User;


use App\Classes\AramexAPI;
use App\Http\Controllers\Controller;
use App\order;
use App\order_printed;
use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Session;
use App\constans;
use App\Notifications\ExciptionConnection;

include_once(app_path() . '/WebClientPrint/WebClientPrint.php');

use Neodynamic\SDK\Web\WebClientPrint;
use App\Models\carrier_devied;
use App\Classes\Smsa;
use App\store;

class AwbPrintConroller extends Controller
{

    public function index()
    {
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());

        return view('m_design.Awb.AwbPrint', ['wcpScript' => $wcpScript]);
    }

    public function check_shiping_number(Request $request)
    {
        $order = order::where([['shipping_number', $request->shiping_number], ['active', '1'], ['processing_status', '1']])->get();

        if ($order->isEmpty()) {
            return response()->json([
                'status' => false
            ]);
        } elseif ($order[0]->order_printed == null) {

            if (count($order) > 0) {
                order_printed::create([
                    'order_id' => $order[0]->id,
                    'count' => 0
                ]);
            }


            return response()->json([
                'status' => true,
                'order_id' => $order[0]->id,
                'count' => 1
            ]);

        } else {
            $count = $order[0]->order_printed->count;
            return response()->json([
                'status' => true,
                'order_id' => $order[0]->id,
                'count' => $count + 1
            ]);
        }
    }

    public function check_devid_shipping_number(Request $request)
    {

        $order = order::where([['shipping_number', $request->shiping_number], ['active', '1'], ['processing_status', '1']])->get();

        if ($order->isEmpty()) {
            return response()->json([
                'status' => false,
                'msg' => 'pleas enter valid shipping number'
            ]);
        } else {

            return response()->json([
                'status' => true,
                'carrier' =>
                //$cc
                    carrier_devied::with('carrier')->get()
            ]);


        }
    }

    public function devied(Request $request)
    {

        $constans = constans::where('name', 'ship_edig')->first();
        ///this constant for shipedge error
        if ($constans->value) {
            try {

                $order = DB::connection('shipedge')->table('shipping_in')
                    // ->where('MLVID', '21474')
                    ->where('MLVID', $request->shiping_number)
                    ->first();
            } catch (\Exception $e) {

                user::first()->slackChannel('systemError')->notify(new ExciptionConnection($e->getMessage()));
                $constans->value = 0;
                $constans->save();
            }
            $sotre = store::where('account_id', $order->AcountID)->first();
            if ($request->carrier == 9) {
                $order->isDevide = 1;
                $order->newQty = $request->qty;
                $order->MLVID = $order->MLVID . 'div';
                $order->sender_name = $sotre->name;
                $response = Smsa::create_shipment($order);
                $res = order::where([['shipping_number', $request->shiping_number], ['active', '1'], ['processing_status', '1']])->first();
                $newOrder = clone $res;
                $newOrder = $newOrder->toArray();
                unset($newOrder['id']);
                $newOrder['carrier'] = 'Smsa';
                $newOrder['ship_method'] = 'Smsa';
                $newOrder['awb_url'] = $response['waybill_url'];
                $newOrder['tracking_number'] = $response['tracking_number'];
                order::create($newOrder);
                $res->active = 0;
                $res->comments = 'devided';
                $res->processing_status = 0;
                $res->save();
                if ($res) {
                    return [
                        'status' => true
                    ];
                }
            } else if ($request->carrier == 2) {
                //  dd($request);
                $order->isDevide = 1;
                $order->newQty = $request->qty;
                $order->MLVID = $order->MLVID . 'div';
                $order->sender_name = $sotre->name;
                $order->sender_email = $sotre->email;
                $response = AramexAPI::create_shipment($order);
                //dd($response);
                $res = order::where([['shipping_number', $request->shiping_number], ['active', '1'], ['processing_status', '1']])->first();
                $newOrder = clone $res;
                $newOrder = $newOrder->toArray();
                unset($newOrder['id']);
                $newOrder['carrier'] = 'Aramex';
                $newOrder['ship_method'] = $res->country=='SA' ? 'EAMXDOM' : 'EAMXEPE';
                $newOrder['awb_url'] = $response['waybill_url'];
                $newOrder['tracking_number'] = $response['tracking_number'];
                order::create($newOrder);
                $res->active = 0;
                $res->comments = 'devided';
                $res->processing_status = 0;
                $res->save();
                if ($res) {
                    dd($res);
                    return [
                        'status' => true
                    ];
                }
            }


        }
    }

}
