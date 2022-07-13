<?php

namespace App\Http\Controllers\QZ;

use App\Classes\AramexAPI;
use App\Classes\FDA;
use App\Classes\Mkhdoom;
use App\Classes\Smsa;
use App\Classes\Wadha;
use App\Classes\Zajil;
use App\constans;
use App\daliay;
use App\Imports\orderImport;
use App\order;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Neodynamic\SDK\Web\PrintFilePDF;

class QZController extends Controller
{
   public function getPDF(Request $request){
     $order=order::where([['shipping_number',$request->shippingNumber],['active',1]])->first();

       if($order->carrier=='Aramex'){

           $url=AramexAPI::create_label($order->tracking_number);
           $filePath=$url;
       }elseif($order->carrier=='Smsa' ||$order->carrier=='SMSA'){
           $url=   Smsa::create_label($order->tracking_number,false);
           $filePath=$url;
       }elseif($order->carrier=='Mkhdoom'){
           $url= Mkhdoom::create_label($order->tracking_number);
           $filePath=$url;
       }elseif($order->carrier=='FDA'){
           $url= FDA::create_label($order->tracking_number);
           $filePath=$url;
       }elseif($order->carrier=='Zajil'){
           $url= Zajil::create_label($order->tracking_number);
           $filePath=$url;
       }elseif($order->carrier=='Wadha') {
           $url = Wadha::create_label($order->tracking_number);
           $filePath = $url;
       }else{
           $url = $order->awb;
       }
      return response()->json([
          'url'=>$url
      ]);
   }
}
