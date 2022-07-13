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


class ReturnReport extends Controller
{
    public function index()
    {

        return view('newDesign.Client.mainPage.returnReport');
    }


    public function get_ordres(Request $request,$flag=false)
    {
        $orders = order::query();

        $orders->where('store_id', '=', auth()->user()->store->account_id);

        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $orders->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween('created_at', [$from, $to]);
            }
        }
        $orders = $orders->where('order_status', 'Returned');

        if ($flag) {
            return $orders;
        } else {
            return Datatables::of($orders->with('carriers')->get())
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
                $data[$i]['shipping_date']=$or->shipping_date;

                $i++;
            }

            return Excel::download(new CodReportExport($data), 'CodReport.xlsx');
        }









}
