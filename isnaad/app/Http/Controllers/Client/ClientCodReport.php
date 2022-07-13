<?php

namespace App\Http\Controllers\Client;

use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Yajra\DataTables\DataTables;
use App\Exports\Client\CodReportExport;
use Maatwebsite\Excel\Facades\Excel;
class ClientCodReport extends Controller
{
    public function index()
    {
        $carreires = carrier::all();
        return view('m_design.Client.mainPage.orderReport',['carriers' => $carreires]);
    }


    public function get_ordres(Request $request,$flag=false)
    {
        $orders = order::query();

        $orders =  $orders->where('store_id', '=', auth()->user()->store->account_id);
        $orders=  $orders->where('active', '=', '1');
        if ($request->has('carrier') && $request->get('carrier') != '') {


            $orders = $orders->where('carrier', $request->carrier);


        }

        if($request->has('date_type')) {
            $date_type=$request->date_type;
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = Carbon::parse($request->get('to'))->addDay()->format('Y-m-d');
                $from = Carbon::parse($request->get('to'))->format('Y-m-d');

                $orders = $orders->whereBetween($date_type, [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween($date_type, [$from, $to]);
            }
        }
        if ($request->has('status')) {
            if ($request->get('status') == 1) {
                $orders = $orders->where('order_status', 'inTransit');
            } elseif ($request->get('status') == 2) {
                $orders = $orders->where('order_status', 'Returned');
            } elseif ($request->get('status') == 3) {
                $orders = $orders->where('order_status', 'Delivered');
            }elseif ($request->get('status') == 4) {
                $orders = $orders->where('order_status', 'Data Uplouded');
            }

        }
        if ($request->has('type')) {
            if ($request->get('type') == 2) {
                $orders = $orders->where([['cod_amount', '=', 0]]);
            } elseif ($request->get('type') == 1) {
                $orders = $orders->where([['cod_amount', '>', 0]]);
            }
        }
        $orders->orderByDesc($date_type);


        if ($flag) {
            return $orders;
        } else {
            return Datatables::of($orders->with('carriers'))
                ->make(true);
        }

    }
    public function export_client_cod(Request $request){
        $order=  $this->get_ordres($request,true);
        $order=$order->get();
        $data=[];
        $i=0;
        foreach ($order as  $or){
            $data[$i]['shipping_number']=$or->shipping_number;
            $data[$i]['order_number']=$or->order_number;
            $data[$i]['carrier']=$or->carrier;
            $data[$i]['cod_amount']=$or->cod_amount;
            $data[$i]['tracking_number']=$or->tracking_number;
            $data[$i]['order_status']=$or->order_status;
            $data[$i]['name']=$or->fname;
            $data[$i]['phone']=$or->phone;
            $data[$i]['city']=$or->city;
            $data[$i]['address']=$or->address_1;
            if($or->cod_amount==0){
                $data[$i]['methode']="Paid";
            }else{
                $data[$i]['methode']="COD";
            }

            $data[$i]['delivery_date']=$or->delivery_date;
            $data[$i]['shipping_date']=$or->shipping_date;
            $data[$i]['created_at']=$or->created_at;

            $i++;
        }
        $dt=Carbon::now()->toDateString();
        return Excel::download(new CodReportExport($data), auth()->user()->name.'_CodReport_'.$dt.'.xlsx');
    }










}
