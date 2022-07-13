<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\constans;
use App\daliay;
use App\Exports\CarrierReportExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Imports\importPick;
use App\Imports\orderImport;
use App\Imports\tracking;
use App\Exports\trakingExport;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use App\Exports\OrderExportInoiceReport;


class isnaddDelivery extends Controller
{
    public static $suc = 0;

    public function index()
    {

        return view('newDesign.mainPage.isnaad_delivery');
    }


    public function import(Request $request)
    {
        $security_key = constans::where('name', 'security_key')->get();

        if ($security_key[0]->value != $request->security_key) {
            return \redirect()->back()->withErrors(['security key not valid']);
        }


        $data = Excel::toArray(new importPick, request()->file('file'));


        collect(head($data))
            ->each(function ($row, $key) {

                $order = order::where([['shipping_number', $row['shipping_num'], ['active', '1']]])->first();
                if ($order) {
                    if ($row['status'] != null) {

                        $order->order_status = $row['status'];
                        $order->save();
                        self::$suc++;
                    }
                    if ($row['delivery_date'] != null) {

                        $date = $row['delivery_date'];
                        $order->delivery_date = $date;
                        $order->save();
                    }

                }
            });

        return redirect()->back()->with('msg', self::$suc . ' : orders  updated successfully');


    }

    public function traking_view()
    {
        return view('m_design.test_upload');
    }

    public function tr_import()
    {

        $data = Excel::toArray(new tracking(), request()->file('file'));
        $i = 0;
        $newArray = array();
        foreach ($data[0] as $row) {
            $newArray[$i] = $row['tracking_number'];
            $i++;
        }
        $newArray = order::with('store')
            ->wherein('tracking_number', $newArray)->where('active', '1')->get();
        $all_orders_group_carrier = $newArray->groupBy('carrier');
        $all_carrier = $newArray->groupBy('carrier')->toArray();

        return Excel::download(new trakingExport($all_carrier, $all_orders_group_carrier), 'ordersss.xlsx');

        // dd(self::$all_order);
    }
}
