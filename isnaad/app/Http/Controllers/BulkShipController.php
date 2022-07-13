<?php

namespace App\Http\Controllers;

use App\constans;
use App\daliay;
use App\Imports\orderImport;
use App\order;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date;
class BulkShipController extends Controller
{
     static $shipped = 0;
    static $error = 0;
    public function index(){
        return view('m_design.bulk_ship');
    }
    public function import(Request $request)
    {
       
        $security_key = constans::where('name', 'security_key')->get();

        if ($security_key[0]->value != $request->security_key) {
            return \redirect()->back()->withErrors(['security key not valid']);
        }
        try {
            $shipped = 0;
            $error = 0;
            $data = Excel::toArray(new orderImport(), request()->file('file'));
         //  dd($data);
            collect(head($data))
                ->each(function ($row, $key) {
                    $order =
                        order::where('tracking_number', $row['tracking_number'])
                            //->where('carrier', $row['carrier'])
                        ->where('processing_status', 1)
                         ->where('active', 1)
                            ->with('store')
                            ->first();
                            
                        // dd($order);
                    //

                    if ($order) {
                        
                        self::$shipped++;
                       
                             $shipping_date1=Carbon::now()->format('Y-m-d H:i:s');
                        
                         if($order->carrier=='Shipox'){
                            $order->update(['shipping_date' =>  $shipping_date1, 'processing_status' => 0,'shiping_date_time'=>$shipping_date1,'order_status'=>'Delivered']);

                        }
                        elseif($order->carrier=='Pick'){
                            $order->update(['shipping_date' =>  $shipping_date1, 'processing_status' => 0,'shiping_date_time'=>$shipping_date1,'order_status'=>'Delivered','shipping_charge'=>5]);

                        }
                        else{
                            $order->update(['shipping_date' =>  $shipping_date1, 'processing_status' => 0,'shiping_date_time'=>$shipping_date1,'order_status'=>'inTransit']);

                        }
                        
                       
//                        $dataArray = [
//                            'MLVID' => $order->shipping_number,
//                            'orderID' => $order->shipping_number,
//                            'AcountID' => $order->account_id,
//                            'statusOut' => 'shipped',
//                            'TrackingNumber' => $order->tracking_number,
//                            'Carrier' => $order->carrier,
//                            'Ship_Method' => $order->ship_method,
//                            'FinalPostage' => $order->shipping_charge + $order->cod_charge,
//                            'TypeShipping' => 'Isnaad_App'
//                        ];
                        //  DB::connection('shipedge')->table('shipping_out')->insert($dataArray);
//dd($order->store->api_key,$order->store->name,$order->store_id,$order->store->account_id);
             if($order->carrier=='Aramex' && $order->country=='SA'){
             $order->ship_method='EAMXDOM';
             }elseif($order->carrier=='Aramex' && $order->country !='SA'){
                 $order->ship_method='EAMXEPE';
             }elseif($order->carrier=='DOS' ){
                  $order->carrier="DOC";
                  $order->ship_method='DOC';
             }elseif($order->carrier=='Naqel'){
                  $order->carrier="Naqel";
                  $order->ship_method='NAQEL';
             }
                        $data = [
                             "username"=>"manager",
                            "key" => '78c60a57f240395e39c67a230b74095c',
                            "warehouse" => "isnaad",
                            "account_id" => $order->store_id,
                            "order" => [
                                "id" => $order->shipping_number,
                                "carrier" => $order->carrier,
                                // "carrier" => 'auto-ship',
                                "shipping_method" => $order->ship_method,
                                 //"shipping_method" => 'auto-ship',
                                "tracking_number" => $order->tracking_number,
                                "final_postage" => $order->shipping_charge+$order->cod_charge
                            ]
                        ];
                              // dd($data);            
                        $header = array("Content-Type: application/json");
                        $client = new GuzzleHttpClient();
                  
                       $res= $client->post('http://epsilonintegration.shipedge.com/API/Rest/v2/Orders/mark_as_shipped',
                            [ 'headers' => $header,'body' => json_encode($data )]
                        );

                        $res=json_decode($res->getBody());
                       
                         if(isset($res->description)){
                             Log::error($res->description."   ".$order->ship_method);
                         }
                       
                     
                    } else {
                        self::$error++;
                        //   DB::connection('mysql')->enableQueryLog();
                        // DB::connection('mysql')->table('users')->where('id','1')->update(['name'=>'adminn']);

                        //   $queries = DB::getQueryLog();

                        // dd(end($queries));

                       // Log::error('Order not found ' . $row['tracking_number']);
                    }
                });
            $file = $request->file('file');

            $time = microtime('.') * 10000;
         $filename = $time . '.' . strtolower($file->getClientOriginalExtension());
        $real_name = $file->getClientOriginalName();
      $destination = 'Daliay';

         $real_name = pathinfo($real_name, PATHINFO_FILENAME);
          $file->move($destination, $filename);

            daliay::create([
               'real_name' => $real_name,
               'storage_name' => $filename,
                'user_id' => auth()->user()->id
            ]);

            return back()->with('success', self::$shipped . ' Orders imorted' . ' - ' . self::$error . ' not imported');
            return \redirect('bulk_ship');
        } catch (\Exception $exception) {
            Log::error('error found ' . $exception->getMessage());
            return 0;
        }
    }
     public function import_return(Request $request)
     {
        

        if($request->method()=='GET'){
         return view('import');
        }
      $data = Excel::toArray(new orderImport(), request()->file('file'));

        collect(head($data))->each(function ($row, $key){

       // $order=  order::where([['shipping_number',$row['shipping_number']],['active',1]])->first();
  // $mm= Date::excelToDateTimeObject($row['delivery_date'])->format('Y-m-d');
 
          $order=  order::where([['shipping_number',$row['shipping_number']],['active',1]])->first();

           if($order){
             //  $order->update(['order_status'=>'Delivered','delivery_date'=>'2021-02-01']);
             $order->update(['order_status'=>'Returned','Last_Status'=>'Restocking']);
           }else{
           Log::alert('not found order shipping number =  '.$row['shipping_number']);
           }

        });
    }
}
