<?php

namespace App\Http\Controllers\User;


use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\order_printed;
use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\store;

class ChangeAwb extends Controller
{

    public function index()
    {
        $carrier = carrier::where('active', 1)->get();
        return view('m_design.Awb.Change_Awb', ['carriers' => $carrier]);
    }

    public function changeAwb(Request $request)
    {

        $request->validate(['shipping' => 'required', 'carrier' => 'required']);

        $order = order::where([['shipping_number', $request->shipping], ['active', '1'],['processing_status','1']])->first();
        if ($order) {
            $comment='change To ' . $request->carrier . ' by ' . auth()->user()->name;
            $order->processing_status=0;
            $order->active=0;
            $order->Comments=$comment;
            $order->save();

            return redirect()->back()->with(['success' => 'The change was successful']);

        } else {
            return redirect()->back()->withErrors(['er' => 'this order is not found ']);
        }

    }

}
