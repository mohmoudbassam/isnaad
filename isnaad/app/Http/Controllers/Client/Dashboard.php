<?php

namespace App\Http\Controllers\Client;

use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;


class Dashboard extends Controller
{
    public function index()
    {
        $carreires = carrier::all();

        //dd($wcpScript);
        $array = [
            'carriers' => $carreires,
            'cities'=>\App\city::all()
        ];
        return view('m_design.Client.mainPage.dashboard',$array);
    }

    public function statisticDashbord(Request $request){
        $order = order::Active();
        //chart 1
       // dd(auth()->user());
        $order=$order->where('store_id',auth()->user()->store->account_id);
        if ($request->has('carrier') && $request->get('carrier') != '') {
            $order->where('carrier', $request->carrier);
        }
        if ($request->has('store') && $request->get('store') != '') {
            $order->where('store_id', $request->store);
        }
        if ($request->has('city') && $request->get('city') != '') {
            $order->where('city', $request->city);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {


                $to = new \DateTime($request->get('to'));
                $to = $to->add(new DateInterval('P1D'));
                $to = $to->format('y/m/d');

                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $order->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $order->whereBetween('created_at', [$from, $to]);
            }
        }
        $Shippedstatus = $order->select(DB::raw('count(*) as count ,order_status'))->groupBy('order_status')->get();

        $allCount = $Shippedstatus->sum(function ($status) {
            return $status->count;
        });
        $Shippedstatus->map(function ($status) {
            return [
                'status' => $status->order_status,
                'count' => $status->count,

            ];
        });


        //chart 2  cod chart
        $codOrders = order::query()->Active()
            ->select(DB::raw('count(*) as count ,order_status'))
            ->where('cod_amount', '>', 0)
          ->where('store_id',auth()->user()->store->account_id)
            ->groupBy('order_status');

        if ($request->has('carrier') && $request->get('carrier') != '') {
            $codOrders->where('carrier', $request->carrier);
        }
        if ($request->has('store') && $request->get('store') != '') {
            $codOrders->where('store_id', $request->store);
        }
        if ($request->has('city') && $request->get('city') != '') {
            $codOrders->where('city', $request->city);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {


                $to = new \DateTime($request->get('to'));
                $to = $to->add(new DateInterval('P1D'));
                $to = $to->format('y/m/d');

                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $codOrders->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $codOrders->whereBetween('created_at', [$from, $to]);
            }
        }
        $codOrders = $codOrders->get();
        $codOrders->map(function ($status) {
            return [
                'status' => $status->order_status,
                'count' => $status->count,

            ];
        });
        ///chart 3  paid chart

        $paidOrders = order::query()->Active()
            ->where('store_id',auth()->user()->store->account_id)
            ->select(DB::raw('count(*) as count ,order_status'))
            ->where('cod_amount', '=', 0)
            ->groupBy('order_status');
        if ($request->has('carrier') && $request->get('carrier') != '') {
            $paidOrders->where('carrier', $request->carrier);
        }
        if ($request->has('store') && $request->get('store') != '') {
            $paidOrders->where('store_id', $request->store);
        }
        if ($request->has('city') && $request->get('city') != '') {
            $paidOrders->where('city', $request->city);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {


                $to = new \DateTime($request->get('to'));
                $to = $to->add(new DateInterval('P1D'));
                $to = $to->format('y/m/d');

                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $paidOrders->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $paidOrders->whereBetween('created_at', [$from, $to]);
            }
        }
        $paidOrders = $paidOrders->get();
        $paidOrders->map(function ($status) {
            return [
                'status' => $status->order_status,
                'count' => $status->count,
            ];
        });


        return response()->json([
            'shipped_order' => $Shippedstatus,
            'allCount' => $allCount,
            'codOrders' => $codOrders,
           'codCount' => $codOrders->sum(function ($status) {
              return $status->count;
            }),
           'paidOrders' => $paidOrders,
            'paidCount' => $paidOrders->sum(function ($status) {
                return $status->count;
           }),
          'chart4' => $this->getChart4($request),///chart 4,
      'chart6' => $this->getChart6($request),
         'chart7'=>$this->getChart7()


        ]);
    }

    private function getChart4($request)
    {
        //  $year=Carbon::now()->year;
        $order = order::query();
        if ($request->has('carrier') && $request->get('carrier') != '') {

            $order = $order->where('carrier', $request->carrier);
        }
        if ($request->has('store') && $request->get('store') != '') {
            $order->where('store_id', $request->store);
        }
        if ($request->has('city') && $request->get('city') != '') {
            $order->where('city', $request->city);
        }
        if ($request->has('from') && $request->get('from') != '') {

            $year = new \DateTime($request->get('from'));
            $year = $year->format('Y');


        }
        $year = isset($year) ? $year : Carbon::now()->year;
        $order->whereYear('created_at', $year);
        $order = $order
            ->select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"), DB::raw('YEAR(created_at) year, MONTH(created_at) month'), 'order_status')
            ->Active()
            ->where('store_id',auth()->user()->store->account_id)
            ->where(function ($q) {
                $q->where('order_status', 'Delivered')
                    ->orwhere('order_status', 'Returned');
            })
            ->groupby('month', 'order_status')
            ->get();
        //->toSqlWithBind();
        // dd($order);
//dd($order->toSqlWithBind());

        return [
            'Delivered' => $order->where('order_status', 'Delivered')->where('month', '!=', null)->sortBy('month')->toArray(),
            'Returned' => $order->where('order_status', 'Returned')->where('month', '!=', null)->sortBy('month'),
            'month' => $order->where('month', '!=', null)->sortBy('month')->groupBy('month'),
            'countByMonth' => $order->where('month', '!=', null)->sortBy('month')->groupBy('month')->map(function ($month) {
                return $month->sum(function ($status) {
                    return $status->data;
                });
            })
        ];
        //  return $order->groupBy('month');
    }
    ///top 10 city
    private function getChart6($request)
    {
        $order = order::Active();
        if ($request->has('carrier') && $request->get('carrier') != '') {
            $order = $order->where('carrier', $request->carrier);
        }
        if ($request->has('store') && $request->get('store') != '') {
            $order->where('store_id', $request->store);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {


                $to = new \DateTime($request->get('to'));
                $to = $to->add(new DateInterval('P1D'));
                $to = $to->format('y/m/d');

                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $order->whereBetween('created_at', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $order->whereBetween('created_at', [$from, $to]);
            }
        }

        return $order
            ->select('city', DB::raw('count(id) as count'))
            ->where('store_id',auth()->user()->store->account_id)
            ->groupBy('city')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get()
            ->pluck('count', 'city')
            ->toArray();

    }
    public function getChart7()
    {
        $order = order::Active();
        return  $order
            ->select(DB::raw('count(carrier)'),'carrier',DB::raw('AVG(DATEDIFF(delivery_date,shipping_date))*24 as diff'))
            ->where('order_status','Delivered')
            ->where('store_id',auth()->user()->store->account_id)
            ->groupBy('carrier')
            ->get()
            ->pluck('diff','carrier');

    }




}
