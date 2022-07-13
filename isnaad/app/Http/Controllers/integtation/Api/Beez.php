<?php

namespace App\Http\Controllers\integtation\Api;

use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\order_status;
use App\store;
use App\user;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Yajra\DataTables\DataTables;
use App\Exports\Client\CodReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Validation\Validator;

class Beez extends Controller
{

    public function update_status(Request $request)
    {

        $validate = \validator($request->all(), [
            'tr' => 'required',
            'status' => 'required'
        ]);

        if ($validate->fails())
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "code" => "",
                    "name" => "",
                    "description" => $validate->errors()
                ]

            ]);
        $traking_number_api_key = $request->tr;
        $traking_number_api_key = explode(',', $traking_number_api_key);
        $traking_number = $traking_number_api_key[0];
        $api_key = $traking_number_api_key[1];
        $stauts = $request->status;
        if($request->created){
            $delivery_Date=$request->created;
            $delivery_Date = date('y-m-d',strtotime($delivery_Date));
        }


        if ($api_key != 'I4WC4nryOA8fvnXLNCX9DI3cnIhFvX') {
            return response()->json([
                "status" => "error",
                "message" => [
                    "type" => "error",
                    "code" => "",
                    "name" => "",
                    "description" => "api key  invalid"
                ]

            ]);
        }
        $beez_order = order::where([['tracking_number', $traking_number], ['carrier', 'Beez'], ['active', '1']])->first();
        if ($stauts == 'OR') {
            $beez_order->order_status = 'Returned';
            $beez_order->Last_Status= 'Return';
            $beez_order->save();
        } elseif ($stauts == 'DD') {

            $beez_order->order_status = 'Delivered';
            isset($delivery_Date)? $beez_order->delivery_date=$delivery_Date:$beez_order->delivery_date=null;
            $beez_order->Last_Status= 'DeliveryDone';
            $beez_order->save();
        } elseif ($stauts == 'OCANCEL') {
            $beez_order->order_status = 'Returned';
            $beez_order->Last_Status= 'Return';
            $beez_order->save();
        }else{
            if($beez_order->order_status!='Delivered'){
                $beez_order->order_status = 'inTransit';
                $last_status=order_status::where([['carrier_status',$stauts],['carrier','Beez']])->first();
                $beez_order->Last_Status=$last_status->description;
                $beez_order->save();
            }

        }

        return response()->json([

            "status" => "success",
            "message" => [
                "type" => "success",
                "code" => "",
                "name" => "",
                "description" => "Operation Succeeded."
            ]


        ]);
    }


}
