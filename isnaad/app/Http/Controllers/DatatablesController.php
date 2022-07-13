<?php

namespace App\Http\Controllers;
use App\Classes\AramexAPI;
use App\Classes\Mkhdoom;
use App\order;
use App\store;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class DatatablesController extends Controller
{
    public function index()
    {
        //(new HomeController)->getOrdersFromShipedge();
        return view('index');

    }

    public function orderData()
    {
        $orders = order::where('processing_status',1)->get();

        foreach ($orders as $order){
            $store = store::where('account_id',$order->store_id)->first();
            $order->store_name = $store->name;
        }
        return Datatables::of($orders)->make(true);
    }

}
