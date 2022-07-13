<?php

namespace App\Http\Controllers\Client;

use App\carrier;
use App\Http\Controllers\Controller;
use App\Models\order_cancel;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Yajra\DataTables\DataTables;
use App\Exports\Client\CodReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\store_cancel;
class ClientCancelReport extends Controller
{
    public function index()
    {
      $cancel_store = auth()->user()->store->store_cancel;
      
        if(!$cancel_store){
            $cancel_store  = store_cancel::create([
               'store_id' =>auth()->user()->store->id,
                'total_order'=>0,
                'pages'=>1,
                'page_check'=>0
            ]);
           
            self::getCnacel($cancel_store);
        }else{
            self::getCnacel($cancel_store);
        }


       return view('newDesign.Client.mainPage.cancel');
    }

    public static function getCnacel($cancel_store){
//dd($cancel_store);
        $store = auth()->user()->store;
        $header = ["Content-Type: application/json"];
        $data = [
            "key" => $store->api_key,
            "warehouse" => "isnaad",
            "account_id" => $store->account_id,
            "page" => 1,
            "status" => "cancel"
        ];

        $client = new GuzzleHttpClient();
        $response = $client->post('http://epsilonintegration.shipedge.com/API/Rest/v2/Orders/get_orders', [
            'headers' => $header,
            'body' => json_encode($data)
        ]);
        $cancelOrder=json_decode($response->getBody()->getContents());
       // dd($cancelOrder);

     //   dd($cancel_store->total_order,$cancelOrder->total_orders);
     if(isset($cancelOrder->total_orders)){
         if($cancelOrder!=null){
        if($cancel_store->total_order!=$cancelOrder->total_orders){

               self::sendRequestCancel($store,$cancelOrder->total_pages+1,$cancelOrder->total_orders);

        }
        }
     }
    }


    public static function sendRequestCancel($store,$page,$total_orders){
        for($i=1;$i<=$page;$i++){
            $header = ["Content-Type: application/json"];
            $data = [
                "key" => $store->api_key,
                "warehouse" => "isnaad",
                "account_id" => $store->account_id,
                "page" =>$i,
                "status" => "cancel"
            ];

            $client = new GuzzleHttpClient();
            $response = $client->post('http://epsilonintegration.shipedge.com/API/Rest/v2/Orders/get_orders', [
                'headers' => $header,
                'body' => json_encode($data)
            ]);
            $cancelOrder=json_decode($response->getBody()->getContents());
           // dd($cancelOrder);
            foreach ($cancelOrder->orders as $order){
                $order_can=  order_cancel::where('order_number',$order->order_number)->first();

               if(!$order_can){

                       order_cancel::create([
                           'order_number'=> $order->order_number,
                           'f_name'=> $order->customer->first_name!=null ? $order->customer->first_name :'none',
                           'city'=> $order->customer->city !=null ?$order->customer->city :'none',
                           'cancel_date'=>date('yy-m-d',strtotime($order->date)),
                           'account_id'=>$store->account_id

                       ]);

               }
            }

        }
        
        $store->store_cancel->update([
                    'total_order'=>$total_orders
        ]);


    }


    public function cancelOrderForTable(){
       $cancel_order= order_cancel::query();
        $cancel_order=  $cancel_order->where('account_id',auth()->user()->store->account_id);
        return Datatables::of($cancel_order->get())
            ->make(true);


    }

    public function cancel_order_show()
    {
        return view('newDesign.Client.mainPage.cancel_order');

    }


      public function cancel_order(Request $request)
    {
        $url = 'http://epsilonintegration.shipedge.com/API/Rest/v2/Orders/cancel_order';
        $store = auth()->user()->store;
        $header = ["Content-Type: application/json"];
        $data = [
            "key" => $store->api_key,

            "warehouse" => "isnaad",
            "account_id" => $store->account_id,
            "order_number" => $request->order_number
        ];

        $client = new GuzzleHttpClient();
        $response = $client->post($url, [
            'headers' => $header,
            'body' => json_encode($data)
        ]);
        $cancelOrder = json_decode($response->getBody()->getContents());
          if($cancelOrder->status=='error'){
              return redirect()->back()->with('fail','pleas try again ');
          }else{
              return redirect()->back()->with('suc','order canceled successfully ');
          }
    }


}
