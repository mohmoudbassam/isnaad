<?php

namespace App\Http\Controllers\Client;

use App\carrier;
use App\city;
use App\Classes\AramexAPI;
use App\Classes\Smsa;
use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\isnaad_return;
use App\Models\order_cancel;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use http\Env\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use App\Classes\Tamex;

use App\Models\store_cancel;
use Yajra\DataTables\DataTables;
class change_carrier extends Controller
{
    use helper;

    public function index()
    {
        $carries = carrier::all();
        return view('m_design.Client.mainPage.make_return', ['carries' => $carries]);
    }

    public function change_carrier_action(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'shipping_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }


        $order = order::where([['shipping_number', $request->shipping_number],
            ['store_id', auth()->user()->store->account_id],
            ['active', '1']
        ])->first();
        if (!$order) {
            return response()->json(['shipping_number' => 'invalid shipping number']);
        }
        if ($order->order_status != 'Delivered') {
            return response()->json(['Delivered' => 'order must be Delivered please contact with isnaad team']);
        }
         $country = $order->country;
        $city = $order->city;

        if ($country == 'SA') {
            if($city=='Riyadh'){
             $cc[] = 'FDA';
             $cc[] = 'Wadha';
            return response()->json(['status' => 'tr', 'carriers' => $cc]);
                //$carrier = carrier::query()->where('active', 1)->get();

               // return response()->json(['status' => true, 'carriers' => $carrier]);
            }else{
                $city = city::where('name', $city)->first();
                $cc = carrier_city::where('city_id', $city->id)->with('carrier')->get();
                $cc = $cc->pluck('carrier')->collapse()->pluck('name')->toArray();
                $cc[] = 'Aramex';
                $cc[] = 'Smsa';
                return response()->json(['status' => 'tr', 'carriers' => $cc]);
            }

        } else {
            $city = city::where('name', $city)->first();
            $cc = carrier_city::where('city_id', $city->id)->with('carrier')->get();
            $cc = $cc->pluck('carrier')->collapse()->pluck('name')->toArray();
            $cc[] = 'Aramex';

            return response()->json(['status' => 'tr', 'carriers' => $cc]);
        }

    }


    public function make_return(Request $request)
    {
        $check_return_order=  isnaad_return::where('shipping_number',$request->shipping_number)->first();

        if( $check_return_order!=null){
            return response()->json([
                  'found'=>true
            ]);
        }
        $order = order::where([['shipping_number', $request->shipping_number],
            ['store_id', auth()->user()->store->account_id],
            ['active', '1']
        ])->first();

        if ($request->carrier == 'Aramex') {
            $result = AramexAPI::create_return_shipment($order);
            //  dd($result);
        } else {
          $path = 'App\Classes\\' . $request->carrier;
            $result = $path::create_return_shipment($order);


        }
          if ($result['status'] == 'success') {
             $carrier=  carrier::where('name',$request->carrier)->first();
              isnaad_return::create([
                  'account_id'=>auth()->user()->store->account_id,
                   'traking_number'=>$result['tracking_number'],
                    'waybill_url'=>$result['waybill_url'],
                 'order_id'=>$order->id,
                  'carrier_id'=>$carrier->id,
                  'shipping_number'=>$request->shipping_number
               ]);
                return \response()->json([
                     'succes' => true
                 ]);
            }else{

                return \response()->json([
               'succes' => false
                ]);
            }


    }
     public function myReturn(){
        return view('m_design.Client.mainPage.MyReturn');
    }
   public function myReturnOrder(){

        $returnOrder=isnaad_return::query();
        $returnOrder->where('account_id', auth()->user()->store->account_id);
        $returnOrder->with(['order','carrier']);
        return Datatables::of($returnOrder)
            ->make(true);
    }


}
