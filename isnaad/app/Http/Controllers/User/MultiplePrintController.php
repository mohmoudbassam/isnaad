<?php

namespace App\Http\Controllers\User;

use App\Classes\Sama;
use App\Http\Controllers\Controller;
use App\Imports\orderImport;
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

use Maatwebsite\Excel\Facades\Excel;
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\PrintFilePDF;
use Neodynamic\SDK\Web\ClientPrintJob;

use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Session;

class MultiplePrintController extends Controller
{
    public function index()
    {

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());
        //dd($wcpScript);

        return view('m_design.ManageOrders.printMultipeAWB', ['wcpScript' => $wcpScript]);
    }

    public function printFile(Request $request)
    {
//dd('ds');
        $data = Excel::toArray(new orderImport(), request()->file('file'));
        $i=0;
        $newArray=array();
        foreach ($data[0] as $row){
            $newArray[$i]=$row['shipping_number'];
            $i++;
        }
            // dd(order::whereIn('shipping_number',$newArray)->Active()->get());
        collect(head($data))
            ->each(function ($row, $key) {
                if($row['shipping_number']!=''){
                    $order =
                        order::where('shipping_number', $row['shipping_number'])
                            ->Active()
                            ->get();


                   //dd($order[0]);
                }
            });
       // dd($request->all());
        if ($request->exists(WebClientPrint::CLIENT_PRINT_JOB)) {
dd('dsf');
            $useDefaultPrinter = ($request->input('useDefaultPrinter') === 'checked');
            $printerName = urldecode($request->input('printerName'));
            //  dd($request->all());

                    $order = order::where([['id',$request->name],['active','1']])->get();

                $order=$order[0];
                //the PDF file to be printed, supposed to be in files folder
                $filePath = $order->awb_url;

                if($order->carrier=='Aramex'){

                    $url=AramexAPI::create_label($order->tracking_number);
                    $filePath=$url;
                }
                elseif($order->carrier=='Smsa' ||$order->carrier=='SMSA'){


                    $url=Smsa::create_label($order->tracking_number,false);

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
                dd($myfile);
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

        }

    }


   public  function get_files_pdf(Request $request){
       $i=0;
       $newArray=array();
       foreach ($request->shipings as $row){
           $newArray[$i]=$row;
           $i++;
       }

    $orders=order::select('carrier','awb_url','tracking_number')->whereIn('shipping_number',$newArray)->Active()->get();
       $allAwb=[];
       foreach ($orders as $order){
           if ($order->carrier == 'Aramex') {

               $url = AramexAPI::create_label($order->tracking_number);
             //  dd($url);
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
               $url=$order->awb_url;
           }

           $allAwb[]=$url;
       }
       return response()->json($allAwb);


   }

}
