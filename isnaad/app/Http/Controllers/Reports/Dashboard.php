<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use DateInterval;

class Dashboard extends Controller
{
    public function index()
    {
         $carriers = \App\carrier::all();
    $stores = \App\store::all();
    $cities = \App\city::all();
    return view('m_design.dashborad', [
        'carriers' => $carriers,
        'stores' => $stores,
        'cities' => $cities
    ]);
    }

    public function get_statistic(Request $request)
    {
        $orders = order::query();
        if ($request->has('carierrs') && $request->carierrs != '') {
            $orders->where('carrier', $request->carierrs);
        }
        if ($request->has('city') && $request->city != '') {

            $orders->where('city', $request->city);
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
        if ($request->has('country') && $request->country != '') {
            if ($request->country == 0) {

                $orders->where('country', 'SA');
            } elseif ($request->country == 1) {
                $orders->where('country', '!=', 'SA');
            }

        }
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }
        $orders = $orders->where('active', '=', '1');
        $orders = $orders->get();
        $allCount = $orders->count();
        $devliverd = $orders->filter(function ($order) {
            return $order->delivery_date != null;
        });
        $devliverd = $devliverd->count();
        $inTransit = $orders->filter(function ($order) {
            return $order->order_status == 'inTransit' || $order->order_status == 'inTrans';
        });
        $inTransit = $inTransit->count();
        $return = $orders->filter(function ($order) {
            return $order->order_status == 'Returned';
        });
        $Data_Uplouded = $orders->filter(function ($order) {
            return $order->order_status == 'Data Uplouded';
        });
        $return = $return->count();
        return response()->json([
            'allCount' => $allCount,
            'devliverd' => $devliverd,
            'inTransit' => $inTransit,
            'Returned' => $return,
            'Data_Uplouded' => $Data_Uplouded->count()
        ]);
    }

    public function get_statistic_cod(Request $request)
    {
        $orders = order::query();
        $orders->where('active', '=', '1');
        if ($request->has('carierrs') && $request->carierrs != '') {

            $orders->where([['carrier', $request->carierrs]]);

        }
        if ($request->has('city') && $request->city != '') {

            $orders->where('city', $request->city);
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
        if ($request->has('country') && $request->country != '') {
            if ($request->country == 0) {

                $orders->where('country', 'SA');
            } elseif ($request->country == 1) {
                $orders->where('country', '!=', 'SA');
            }

        }
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }


        $orders = $orders->where([['cod_amount', '>', 0]])->get();

        $allCount = $orders->count();
        $devliverd = $orders->filter(function ($order) {
            return $order->delivery_date != null;
        });
        $devliverd = $devliverd->count();
        $inTransit = $orders->filter(function ($order) {
            return $order->order_status == 'inTransit' || $order->order_status == 'inTrans';
        });
        $inTransit = $inTransit->count();
        $return = $orders->filter(function ($order) {
            return $order->order_status == 'Returned';
        });
        $Data_Uplouded = $orders->filter(function ($order) {
            return $order->order_status == 'Data Uplouded';
        });
        $return = $return->count();
        return response()->json([
            'allCount' => $allCount,
            'devliverd' => $devliverd,
            'inTransit' => $inTransit,
            'Returned' => $return,
            'Data_Uplouded' => $Data_Uplouded->count()
        ]);
    }

    public function mixedChart(Request $request)
    {
        $order = order::query();
        if ($request->has('carierrs') && $request->carierrs != '') {
            if ($request->has('store') && $request->get('store') != '') {

                $order = $order->whereHas('store', function ($query) use ($request) {
                    $query->where('account_id', '=', $request->get('store'));
                });
            }
            $year = now()->year;
            $orderCountPerMonth = $order->whereYear('created_at', '=', $year)->where('carrier', $request->carierrs)
                ->select('created_at')
                ->where('active', '=', '1')
                ->get()
                ->groupBy(function ($data) {
                    return Carbon::parse($data->created_at)->format('m');
                })->map->count();

            $orderDelvierdCountPerMonth = $order->whereYear('created_at', '=', $year)->where('carrier', $request->carierrs)
                ->select('created_at')
                ->where('active', '=', '1')
                ->whereNotNull('delivery_date')
                ->get()
                ->groupBy(function ($data) {
                    return Carbon::parse($data->created_at)->format('m');
                })->map->count();
        } else {
            if ($request->has('store') && $request->get('store') != '') {

                $order = $order->whereHas('store', function ($query) use ($request) {
                    $query->where('account_id', '=', $request->get('store'));
                });
            }
            $year = now()->year;
            $orderCountPerMonth = $order->whereYear('created_at', '=', $year)
                ->select('created_at')
                ->where('active', '=', '1')
                ->get()
                ->groupBy(function ($data) {
                    return Carbon::parse($data->created_at)->format('m');
                })->map->count();

            $orderDelvierdCountPerMonth = $order->whereYear('created_at', '=', $year)
                ->select('created_at')
                ->where('active', '=', '1')
                ->whereNotNull('delivery_date')
                ->get()
                ->groupBy(function ($data) {
                    return Carbon::parse($data->created_at)->format('m');
                })->map->count();
        }


        return response()->json([
            'ordersOrderBtMonth' => $orderCountPerMonth,
            'year' => $year,
            'orderDelvierdCountPerMonth' => $orderDelvierdCountPerMonth
        ]);


    }

    public function get_statistic_allcarieres(Request $request)
    {
        $orders = order::query();
        $orders->where('active', '=', '1');

        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }
        if ($request->has('city') && $request->city != '') {

            $orders->where([['city', $request->city]]);

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

        $carreires = carrier::select('name')->get();
        $orders = $orders->get()->groupBy('carrier')->map->count();
        return response()->json([
            'carreires' => $carreires,
            'statisticCarriers' => $orders
        ]);

    }

    public function carieres_performance(Request $request)
    {
        $orders = order::query();
        $orders->where('active', '=', '1');
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
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
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween('created_at', [$from, $to]);
            }
        }
        if ($request->has('city') && $request->city != '') {

            $orders->where([['city', $request->city]]);

        }
        $carrierGroped = $orders->whereNotNull('delivery_date')
            ->where('active', '=', '1')
            ->select(['carrier', 'shipping_date', 'delivery_date'])
            ->get()->groupBy('carrier');
        $carrierCount = 0;
        $carrierName = array();

        foreach ($carrierGroped as $carrier => $orders) {

            $allDaysForDelverdPerCarrer = 0;
            $orderCount = 0;
            foreach ($orders as $order) {
                $dilevary = Carbon::parse($order->delivery_date);
                $dilevary = $dilevary->diffInDays($order->shipping_date);
                $allDaysForDelverdPerCarrer += $dilevary;
                $orderCount++;
            }
            $res = number_format($allDaysForDelverdPerCarrer / $orderCount, 1);
            $carrierName[$carrier] = $res;
        }
        $carreires = carrier::select('name')->get();
        return response()->json([
            'carrierName' => $carrierName,
            'carreires' => $carreires
        ]);
    }

    public function Cod_amount(Request $request)
    {
        $orders = order::query();
        $orders->where('active', '=', '1');
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
        if ($request->has('city') && $request->city != '') {

            $orders->where([['city', $request->city]]);

        }
        $orders = $orders->where([['cod_amount', '>', 0]]);
        $carrierGroped = $orders
            ->select(['carrier', 'cod_amount'])
            ->get()->groupBy('carrier');
        $carrierName = array();

        foreach ($carrierGroped as $carrier => $orders) {
            $countCod = 0;
            foreach ($orders as $order) {
                $countCod += $order->cod_amount;
            }
            $carrierName[$carrier] = $countCod;

        }
        $carreires = carrier::select('name')->get();
        return response()->json([
            'carrierName' => $carrierName,
            'carreires' => $carreires
        ]);

    }

    public function get_paid_order(Request $request)
    {
        $orders = order::query();
        $orders->where('active', '=', '1');
        if ($request->has('carierrs') && $request->carierrs != '') {

            $orders->where([['carrier', $request->carierrs]]);

        }
        if ($request->has('city') && $request->city != '') {

            $orders->where('city', $request->city);
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
        if ($request->has('country') && $request->country != '') {
            if ($request->country == 0) {

                $orders->where('country', 'SA');
            } elseif ($request->country == 1) {
                $orders->where('country', '!=', 'SA');
            }

        }
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }


        $orders = $orders->where([['cod_amount', '=', 0]])->get();

        $allCount = $orders->count();
        $devliverd = $orders->filter(function ($order) {
            return $order->delivery_date != null;
        });
        $devliverd = $devliverd->count();
        $inTransit = $orders->filter(function ($order) {
            return $order->order_status == 'inTransit' || $order->order_status == 'inTrans';
        });
        $inTransit = $inTransit->count();
        $return = $orders->filter(function ($order) {
            return $order->order_status == 'Returned';
        });
        $Data_Uplouded = $orders->filter(function ($order) {
            return $order->order_status == 'Data Uplouded';
        });
        $return = $return->count();
        return response()->json([
            'allCount' => $allCount,
            'devliverd' => $devliverd,
            'inTransit' => $inTransit,
            'Returned' => $return,
            'Data_Uplouded' => $Data_Uplouded->count()
        ]);
    }

    public function city_statistic(Request $request)
    {
        $orders = order::query();

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
        $orders = $orders
            ->select(['city'])
            ->get()->groupBy('city');
        $ar = $orders->map(function ($item, $key) {
            return collect($item)->count();
        });
        $topCity = $ar->sort()->reverse()->take(10);
        $cityName = [];
        $i = 0;
        foreach ($topCity as $key => $city) {
            $cityName[$i] = $key;
            $i++;
        }
        return response()->json([
            'cityName' => $cityName,
            'cityStasitsic' => $topCity
        ]);
    }


    public function getStatistic(Request $request)
    {
        $order = order::Active();
        //chart 1

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
            ->where(function ($q) {
                $q->where('order_status', 'Delivered')->orwhere('order_status', 'Returned')->orWhere('order_status', 'inTransit');
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

/////////////top city //////////
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
       ->whereRaw('delivery_date > shipping_date')
       ->groupBy('carrier')
        
       ->get()
       ->pluck('diff','carrier');

    }




}
