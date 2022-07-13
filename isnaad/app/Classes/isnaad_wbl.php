<?php

namespace App\Classes;

use App\carrier;
use App\carrier_city;
use App\city;
use App\store;
use PDF;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Log;
use Mpdf\Tag\Time;

class isnaad_wbl
{
    public static function create_shipment($order)
    {
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $payment_mode=($cod_amount==0) ? 'PAID' : 'CASH';
        $data = [
            'order_no' => $order->orderNum,
            'order_total' => $order->declared_total,
            'cod_amount' => $cod_amount,
            'name' => $order->custFName,
            'address' => $order->custAddress1.' '.$order->custState.' '.$order->custCity. ' '.$order->custZip.' '.$order->custCountry,
            'phone' => $order->custPhone,
            'order_date' => date('Y-m-d',strtotime($order->OrderDate)),
            'payment_mode' => $payment_mode,
            'weight' => $order->WeightSum,
            'pieces' => $order->Qty_Item,
        ];
        $pdf =PDF::loadView('invoice',$data);
        $filename = 'Isnaad-'.$order->orderNum.'.pdf';
        //dd();
        $output = $pdf->output();
        file_put_contents(getcwd().'/isnaad_labels'. "/".$filename, $output);
        $file =Url('/').'/isnaad_labels'. "/".$filename;
        $data2 = [
            'tracking_number' => $order->MLVID,
            'waybill_url' =>$file,
            'status' => 'success',
            'msg' => 'shipment created successfully'
        ];
        return $data2;
    }
}
