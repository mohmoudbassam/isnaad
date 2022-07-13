<?php

namespace App\Http\Controllers;

use App\Classes\Sama;
use App\order;
use Illuminate\Http\Request;
use App\Classes\AramexAPI;
use App\Classes\Smsa;
use App\Classes\Mkhdoom;
use App\Classes\Zajil;
use App\Classes\Wadha;
use App\Classes\FDA;
use Carbon\Carbon;
//*********************************
// IMPORTANT NOTE
// ==============
// If your website requires user authentication, then
// THIS FILE MUST be set to ALLOW ANONYMOUS access!!!
//
//*********************************

//Includes WebClientPrint classes
include_once(app_path() . '/WebClientPrint/WebClientPrint.php');

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\PrintFilePDF;
use Neodynamic\SDK\Web\ClientPrintJob;

use Session;

class PrintPDFController extends Controller
{
    public function index()
    {

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());
        //dd($wcpScript);

        return view('home.printPDF', ['wcpScript' => $wcpScript]);
    }

    public function printFile(Request $request)
    {
            if ($request->has('mas')) {
            if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {
               
              $files=$this->getFiles($request->mas);
            $fileGroup=[];
                $i=0;
                foreach ($files as $file){
                  //  dd($file);
                    $fileGroup[]= new PrintFilePDF( $file,  'MyFile' . uniqid().'pdf', NULL);
                }

                 //Create a ClientPrintJob and set the PrintFile objects
                    $cpj = new ClientPrintJob();
  $cpj->clientPrinter = new DefaultPrinter();
                //set files to print
            $cpj->printFileGroup = $fileGroup;
                return response($cpj->sendToClient())
                    ->header('Content-Type', 'application/octet-stream');
            }
        }

        if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {

            $useDefaultPrinter = ($request->input('useDefaultPrinter') === 'checked');
            $printerName = urldecode($request->input('printerName'));
              //  dd($request->all());
            $ar = explode(',', $request->name);

            if (count($ar) === 1) {
                if($request->has('proccising')){
                    $order = order::where([['id',$request->name],['active','1'],['processing_status','1']])->withoutGlobalScopes()->get();
                     $printInc=$order[0]->order_printed->count;
                     $printInc=$printInc+1;
                        
                     $order[0]->order_printed->update(['count'=>$printInc]);

                }else{
                    $order = order::where([['id',$request->name],['active','1']])->get();
                }
                $order=$order[0];
                //the PDF file to be printed, supposed to be in files folder
                $filePath = $order->awb_url;

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
                }elseif($order->carrier=='Wadha'){
                    $url= Wadha::create_label($order->tracking_number);

                    $filePath=$url;
                }
                $fileName = 'MyFile' . uniqid();
                if($order->carrier=='Smsa' ||$order->carrier=='SMSA' ){

                    $myfile = new PrintFilePDF($filePath, $fileName, null,false);
                }elseif($order->carrier== 'LaBaih'){
                  //dd($filePath);
                    $myfile = new PrintFilePDF($filePath, $fileName, null,true);
                     $myfile->sizing='Fit';
                }else{
                    
                     $myfile = new PrintFilePDF($filePath, $fileName, null,true);
                }
                $myfile->sizing = 0;
                $myfile->autoCenter = true;
               
                //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
                $cpj = new ClientPrintJob();
                $cpj->printFile = $myfile;
                //Create an InstalledPrinter obj
                $myPrinter = new InstalledPrinter(urldecode($request->input('printerName')));
                if ($useDefaultPrinter || $printerName === 'null') {
                 
                    $myPrinter  = new DefaultPrinter();
                    $myPrinter->paperName='A6';
                    $cpj->clientPrinter = $myPrinter;
                } else {
                    $cpj->clientPrinter = new InstalledPrinter($printerName);
                       $cpj->clientPrinter->paperName='A6';
                }
$date_time=Carbon::now()->toDateTimeString();

                $order->printed_time=$date_time;
                   $order->save();
                 //   $order->save();
                //Send ClientPrintJob back to the client
                return response($cpj->sendToClient())
                    ->header('Content-Type', 'application/octet-stream');
            } else {

                $id = $ar[0];
dd('sdf');
                $order = order::find($id);

                //the PDF file to be printed, supposed to be in files folder
                $filePath = $order->awb_url;
                $fileName = 'MyFile' . uniqid();
                $fileName1 = 'MyFile1' . uniqid();


//                if ($useDefaultPrinter || $printerName === 'null') {
//                    $cpj->clientPrinter = new DefaultPrinter();
//                } else {
//                    $cpj->clientPrinter = new InstalledPrinter($printerName);
//                }
                if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {
                    $cpj = new ClientPrintJob();
                    $cpj->printFileGroup = array(
                        new PrintFile($filePath, $fileName, null),
                        new PrintFile($filePath, $fileName1, null)
                    );

                    if ($useDefaultPrinter || $printerName === 'null') {
                        $cpj->clientPrinter = new DefaultPrinter();
                    } else {
                        $cpj->clientPrinter = new InstalledPrinter($printerName);
                    }
$date_time=Carbon::now()->toDateTimeString();

                $order->printed_time=$date_time;
                   $order->save();
                    return response($cpj->sendToClient())
                        ->header('Content-Type', 'application/octet-stream');
                }
            }
        }

    }
     private function getFiles($orders){
        $orders=explode(',',$orders);
      //  dd($orders);
        $array=[];
      foreach ($orders as $order){
          $order=order::where('shipping_number',$order)->first();

          if ($order->carrier == 'Aramex') {

              $url = AramexAPI::create_label($order->tracking_number);
              $filePath = $url;
          } elseif ($order->carrier == 'Smsa' || $order->carrier == 'SMSA') {


              $url = Smsa::create_label($order->tracking_number, false);

              $filePath = $url;
          } elseif ($order->carrier == 'Mkhdoom') {
              $url = Mkhdoom::create_label($order->tracking_number);
              $filePath = $url;
          } elseif ($order->carrier == 'FDA') {
              $url = FDA::create_label($order->tracking_number);
              $filePath = $url;
          } elseif ($order->carrier == 'Zajil') {
              $url = Zajil::create_label($order->tracking_number);
              $filePath = $url;
          } elseif ($order->carrier == 'Wadha') {
              $url = Wadha::create_label($order->tracking_number);

              $filePath = $url;
          }else{
              $myfile = new PrintFilePDF($order->awb_url, 'asd.pdf', null, true);
          }
          $ar[]=$filePath;

      }
        return $ar;
    }


}
