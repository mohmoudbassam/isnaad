<?php


namespace App\Http\Controllers\API;

use App\carrier;
use App\constans;
use App\Helpers\carrier_charge;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\order;

use Illuminate\Http\Request;
use App\order_status;
use Illuminate\Support\Facades\Log;

class UpdateStatusController extends Controller
{
    use carrier_charge;

    public  function Mkhdoom_status(Request $request){

        $validate = \validator($request->all(), [
            'order_number' => 'required',
            'status' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "code" => "",
                    "name" => "",
                    "description" => $validate->errors()
                ]

            ]);
        }
        $order= order::where('tracking_number',$request->order_number)->first();
        Log::alert("MkhdoomStatusBegin " . $request->order_number.'  status '.$request->status);
        if(!$order){
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "code" => "",
                    "name" => "",
                    "description" =>  "this tracking not valid"
                ]

            ]);
        }

        if($request->status == 'completed' || $request->status == 'Delivered' ){
            $dt=Carbon::now();
            $newDate =$dt->toDateString();
            $order->order_status='Delivered';
            $order->delivery_date=$newDate;
            $order->Last_Status=$request->status;
              $order->carrier_charge=$this->MakhdoomCh($order);
            $order->save();

        }elseif($request->status=='returned_to_origin' ){
            $order->order_status='Returned';
            $order->Last_Status=$request->status;
            $order->save();
        }else{
            if($order->order_status!='Delivered' ){
                $order->order_status='inTransit';
                $order->Last_Status=$request->status;

                $order->save();
            }



        }


    }
    public  function GetOrder(Request $request){

        $validate = \validator($request->all(), [
            'order_number' => 'required',
            'api_key'=> 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "code" => "",
                    "name" => "",
                    "description" => $validate->errors()
                ]

            ]);
        }
        $apikey= constans::where([['name','api_key'],['value',$request->api_key]])->first();
        if(!$apikey){
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "code" => "",
                    "name" => "",
                    "description" =>  "Wrong Credentials"
                ]

            ]);
        }
$order=order::whereHas('store',function($q){
$q->where('store_type',1);
})->where([['order_number',$request->order_number],['active',1]])->first();
        //$order= order::where([['order_number',$request->order_number],['active',1]])->first();
        Log::alert("Get order request " . $request->order_number." from client ". self::getClient(substr($request->api_key,37)));
        if(!$order){
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "description" =>  "the order number is not valid"
                ]

            ]);
        }else{

            return response()->json([
                "status" => "success",
                "description" => [
                    "order_status" => self::getStatus($order->order_status),
                    "Shipping Number" => $order->shipping_number,
                    "description" =>  "order found"

                ]

            ]);
        }
    }
    private function getStatus($status)
    {
        if ($status == 'Delivered' || $status == 'delivered') {
            return 2;
        } elseif ($status == 'Returned' || $status == 'Cancelled') {
            return 3;
        } else {
            return 1;
        }
    }
    private function getClient($client)
    {
        if ($client == 'S') {
            return 'Salla';
        } elseif ($client == 'Z') {
            return 'Zid';
        } else {
            return 1;
        }
    }
    public  function Aramex_status(Request $request,$tr_no,$status_code){
        Log::alert("AramexStatusBegin " . $tr_no.'  statusCode '.$status_code);
        $order= order::where([['tracking_number',$tr_no,['carrier','Aramex']]])->first();
        if(!$order){
            return response()->json([
                'status'=>false,
                'error'=>'tracking number does not exist'
            ]);
        }
if($order->order_status=='Delivered'|| $order->order_status=='Returned'){
 return response()->json([
                'status'=>false,
                'error'=>'the order is alreaady updated'
            ]);
}
        $stauts= order_status::where([['isnaad_staus','Delivered'],['carrier','Aramex']])->select('carrier_status')->get()->groupBy('carrier_status')->toArray();
        $stauts= array_key_exists($status_code,$stauts);
        if($stauts){///deliverd

            $order->order_status='Delivered';
            $desctipion=order_status::where([['carrier_status',$status_code],['carrier','Aramex']])->first();
            $dt=Carbon::now();
            $order->order_status='Delivered';
            $order->Last_Status=$desctipion->description;
            $order->delivery_date=$dt->toDateString();
            $order->save();
        }elseif($status_code=='SH069'){
            $dt=Carbon::now();
            $order->return_date_carrier=$dt->toDateString();
          //  $order->order_status='Returned';
            //$order->Last_Status='Returned to Shipper';
            $order->save();
        }else{
            $stauts= order_status::where([['carrier','Aramex'],['carrier_status',$status_code]])->first();
            if($stauts){
                $order->order_status='inTransit';
                $order->Last_Status=$stauts->description;
                $order->save();
            }else{
                return response()->json([
                    'status'=>false,
                    'error'=>'invalid tracking code'
                ]);
            }

        }
        return response()->json([
            'status'=>true,
        ]);
    }

    public function Tamex_status(Request $request)
    {

      $validate = \validator($request->all(), [
            'awb' => 'required|exists:orders,tracking_number',
            'status' => 'required',

        ],[
             'awb.exists'=>'this awb not found'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status'=>'false',
                     'message'=>$validate->errors()
            ],400);
        }else{
          $order=  order::where('tracking_number',$request->awb)->Active()->first();
          if($request->status=='successful'){
              if($order->order_status=='inTransit'){
              $order->update([
                 'order_status'=>'Delivered',
                  'delivery_date'=>$request->UpdateOn
              ]);
              }else{
                         return response()->json([
            'status'=>"the shipment is already updated",

        ],200); 
              }
          }elseif ($request->status=='RTO'){
              if($order->order_status=='inTransit'){
              $order->update([
                  'order_status'=>'Returned',
                  'return_date_carrier'=>$request->UpdateOn
              ]);
              }
              else{
                         return response()->json([
            'status'=>"the shipment is already updated",

        ],200); 
              }
          }else{
              $order->update([
                  'order_status'=>'inTransit',
              ]);

          }


        }
        return response()->json([
            'status'=>"true",

        ],200);

    }

}
