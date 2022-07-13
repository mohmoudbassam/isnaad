<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\Exports\CarrierReportExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use App\Exports\CodReportExport;
use DateInterval;

class CodReportController extends Controller
{
    public function index()
    {
        $carreires = carrier::all();
        $stores = store::all();

        $array = [
            'stores' => $stores,
            'carreires' => $carreires
        ];
        return view('m_design.Reports.codReport', $array);
    }

    public function get_Cod_report(Request $request, $flag = false)
    {

        $orders = order::query();
        $orders = $orders->where('cod_amount', '>', 0);
        $orders->where('active', '=', '1');
        if ($request->has('carrier') && $request->get('carrier') != '') {


            $orders = $orders->where('carrier', $request->carrier);


        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->add(new DateInterval('P1D'));
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
        if ($request->has('status')) {
            if ($request->get('status') == 1) {
                $orders = $orders->where('order_status', 'inTransit');
            } elseif ($request->get('status') == 2) {
                $orders = $orders->where('order_status', 'Returned');
            } elseif ($request->get('status') == 3) {
                $orders = $orders->where('order_status', 'Delivered');
            }
        }
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }

        if ($flag) {
            return $orders;
        } else {
            return Datatables::of($orders->with(['carriers', 'store']))
                ->make(true);
        }


    }

    public function export_cod(Request $request)
    {
        $order = $this->get_Cod_report($request, true);
        $order = $order->get();
        $data = [];
        $i = 0;
        foreach ($order as $or) {
            $data[$i]['shipping_number'] = $or->shipping_number;
            $data[$i]['order_number'] = $or->order_number;
            $data[$i]['carrier'] = $or->carrier;
            if (isset($or->store->name)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['cod_amount'] = $or->cod_amount;
            $data[$i]['tracking_number'] = $or->tracking_number;
            $data[$i]['order_status'] = $or->order_status;
            $data[$i]['name'] = $or->fname;
            $data[$i]['phone'] = $or->phone;
            $data[$i]['shipping_date'] = $or->shipping_date;

            $i++;
        }

        return Excel::download(new CodReportExport($data), 'CodReport.xlsx');
    }


}
