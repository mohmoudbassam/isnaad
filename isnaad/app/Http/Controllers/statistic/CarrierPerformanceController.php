<?php

namespace App\Http\Controllers\statistic;

use App\carrier;
use App\carrier_city;
use App\city;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarrierPerformanceController extends Controller
{
    public function index()
    {

        $carriers = carrier::all();
        $city = city::all();
        $order_status=
            order::toBase()->select(
                DB::raw('sum(case when order_status LIKE  "inTransit" then 1 else 0 end) as inTransit,
                               sum(case when order_status LIKE  "Delivered" then 1 else 0 end) as Delivered,
                               sum(case when order_status LIKE  "Returned" then 1 else 0 end) as Returned'))
            ->first();

        return view('m_design.statistic.carrier_statistic', ['carriers' => $carriers, 'cites' => $city,'order_status'=>$order_status]);
    }

    public function Statistic(Request $request)
    {

        if ($request->has('chart')) {

            return $this->{$request->get('chart')}($request, false);
        } else {

            return response([
                'chart1' => $this->FirstChart($request),
                'chart2' => $this->SecondeChart($request),

            ]);
        }


    }

    public function FirstChart($request, $context = true)
    {

        //  dd($context);
        $orders = order::query();
        if ($request->has('carrier')) {
            $orders = $orders->where('carrier', $request->carrier);
        } else {
            $orders = $orders->where('carrier', 'Aramex');
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                if ($request->get('from') == $request->get('to')) {

                    $orders = $orders->whereDate('created_at', $request->get('from'));
                } else {

                    $to = new \DateTime($request->get('to'));
                    $to = $to->add(new DateInterval('P1D'));
                    $to = $to->format('y/m/d');

                    $from = new \DateTime($request->get('from'));
                    $from = $from->format('y/m/d');
                    $orders = $orders->whereBetween('created_at', [$from, $to]);
                }
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween('created_at', [$from, $to]);
            }
        }
        $orders = $orders->select('city')
            ->selectRaw(DB::raw('ROUND( Avg(DATEDIFF(delivery_date,shipping_date )),2 ) * 24 as day'))
            ->Active()
            ->selectRaw('count(*) as count')
            ->where('order_status', '=', 'Delivered')
            ->whereRaw('delivery_date > shipping_date')
            ->orderBy('day', 'asc')
            ->havingRaw('count(*)>10')
            ->limit(10)
            ->groupBy('city')->get()->map(function ($carrier) {
                return [
                    'count' => $carrier->count,
                    'day' => $carrier->day,
                    'city' => $carrier->city,
                ];
            });
        if ($context) {
            return $orders;
        } else {
            return response([
                'chart1' => $orders
            ]);
        }


    }

    public function SecondeChart($request, $context = true)
    {
        $orders = order::query();
        if ($request->has('city')) {
            $orders = $orders->where('city', $request->city);
        } else {
            $orders = $orders->where('city', 'Riyadh');
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                if ($request->get('from') == $request->get('to')) {

                    $orders = $orders->whereDate('created_at', $request->get('from'));
                } else {

                    $to = new \DateTime($request->get('to'));
                    $to = $to->add(new DateInterval('P1D'));
                    $to = $to->format('y/m/d');

                    $from = new \DateTime($request->get('from'));
                    $from = $from->format('y/m/d');
                    $orders = $orders->whereBetween('created_at', [$from, $to]);
                }
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween('created_at', [$from, $to]);
            }
        }
        $orders = $orders->select('carrier')
            ->selectRaw(DB::raw('ROUND( Avg(DATEDIFF(delivery_date,shipping_date )),2 ) *24 as day'))
            ->selectRaw('count(*) as count')
            ->Active()
            ->where('order_status', '=', 'Delivered')
            ->whereRaw('delivery_date > shipping_date')
            ->orderBy('day', 'asc')
            ->havingRaw('count(*)>10')
            ->limit(10)
            ->groupBy('carrier')->get()->map(function ($carrier) {
                return [
                    'count' => $carrier->count,
                    'hours' => $carrier->day,
                    'carrier' => $carrier->carrier,
                ];
            });
        if ($context) {
            return $orders;
        } else {
            return response([
                'chart2' => $orders
            ]);
        }
    }

    public function third_chart($request, $context = true)
    {

//        \DB::listen(function($q){
//            Log::info($q->sql);
//        });
//
        \DB::enableQueryLog();


        $orders = order::query();
        $toDay=now()->format('Y-m-d');
        $three_month=now()->subMonths(3)->subDays(6)->format('Y-m-d');
        $inTransit_order = $orders
            ->select( DB::raw('Date(shipping_date) as date'),
                DB::raw('sum(case when order_status LIKE  "inTransit" then 1 else 0 end) as inTransit,
                               sum(case when order_status LIKE  "Delivered" then 1 else 0 end) as Delivered,
                               sum(case when order_status LIKE  "Returned" then 1 else 0 end) as Returned'))
            ->whereBetween('shipping_date', [$three_month,$toDay])
            ->whereIn('order_status', ['Delivered', 'Returned', 'inTransit'])
            ->groupBy(DB::raw('Date(shipping_date)'))->get();



        return $inTransit_order;
    }

}
