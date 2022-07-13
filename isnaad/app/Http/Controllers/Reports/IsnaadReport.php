<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\Exports\FinanceCodReport;
use App\Exports\IsnaadFinance;
use App\Http\Controllers\Controller;
use App\interrupted_orders;
use App\Mail\CompleteExportedFile;
use App\Models\masterPlan;
use App\Models\nstoreplan;
use App\order;
use App\statment;
use App\statment_file;
use App\store;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Neodynamic\SDK\Web\WebClientPrint;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Session;
use Yajra\DataTables\DataTables;

class IsnaadReport extends Controller
{
    public static $store = null;
    public static $count = null;

    public function index()
    {

        $stores = store::all();
        $carreires = carrier::all();

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());
        //dd($wcpScript);
        $array = [
            'stores' => $stores,
            'carriers' => $carreires, 'wcpScript' => $wcpScript
        ];
        return view('m_design.Reports.new_isnaad_report', $array);
    }

    public function OrderReportsData(Request $request, $flag = false)
    {


        $orders = order::query()
            ->select('tracking_number',
                'cod_amount', 'created_at', 'awb_url',
                'store_id', 'order_status',
                'delivery_date', 'city', 'inv_num', 'carrier_charge', 'shipping_date', 'address_1', 'weight', 'order_number', 'Last_Status', 'shipping_number', 'carrier', 'country', 'Qty_Item'
                , 'chargalbeWeight', 'actulWeight', 'isnaad_return_date', 'return_date_carrier',

            )->addSelect(
                [
                    'statment_id' =>
                        statment::query()->select('id')
                            ->whereColumn(DB::raw("CONCAT('ISN',orders.inv_num)"), 'statment.inv')
                            ->limit(1)
                ]
            )->where('store_id', '!=', 2);

        $orders = $orders->where('active', '=', '1');
        if ($request->has('place') && $request->get('place') != '') {

            if ($request->get('place') == 1) {
                $orders = $orders->where('country', '!=', 'SA');
            } elseif ($request->get('place') == 2) {
                $orders = $orders->where('country', '=', 'SA');
            } elseif ($request->get('place') == 3) {
                $orders = $orders->where([['country', '=', 'SA'], ['city', '=', 'Riyadh']]);
            } elseif ($request->get('place') == 4) {
                $orders = $orders->where([['country', '=', 'SA'], ['city', '!=', 'Riyadh']]);
            }
        }

        if ($request->has('carierrs') && $request->get('carierrs') != '' && !empty($request->get('carierrs'))) {

            if (is_array($request->carierrs)) {
                $orders = $orders->whereIn('carrier', $request->carierrs);
            } else {
                $orders = $orders->where('carrier', $request->carierrs);
            }

        }

        if ($request->has('cod') && $request->get('cod') != '') {

            if ($request->get('cod') == 1) {
                $orders = $orders->where('cod_amount', '>', 0);
            } elseif ($request->get('cod') == 2) {
                $orders = $orders->where('cod_amount', '=', 0);
            }


        }
        if ($request->has('from') && $request->get('from') != '') {
            $datetype = $request->get('dateType') == 0 ? 'created_at' : 'delivery_date';
            if ($request->get('dateType') == 1) {
                $datetype = 'delivery_date';
            } elseif (is_array($request->get('status')) || $request->get('status') == 'Returned') {

                if (array_search('Returned', $request->get('status'))) {

                    $datetype = 'return_date_carrier';
                }

            } elseif ($request->get('dateType') == 2) {

                $datetype = 'return_date_carrier';
            } elseif ($request->get('dateType') == 3) {

                $datetype = 'shipping_date';
            } else {
                $datetype = 'created_at';
            }

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                if ($request->get('dateType') == 0) {
                    $to = $to->add(new DateInterval('P1D'));
                }

                $to = $to->format('Y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('Y/m/d');

                $orders = $orders->whereBetween($datetype, [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('Y/m/d');
                $to = Carbon::now();
                $to->format('Y/m/d');

                $to = $to->toDateString();
                $orders = $orders->whereBetween($datetype, [$from, $to]);
            }
        }

        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }

        if ($request->has('status') && $request->get('dateType') != 2) {

            if (is_array($request->get('status'))) {

                $orders = $orders->whereIn('order_status', $request->get('status'));
            } else {

                $orders = $orders->where('order_status', $request->get('status'));
            }


        }

        if ($request->has('platform') && $request->get('platform') != '') {
            if ($request->get('platform') == 1) {
                $orders = $orders->whereHas('store', function ($query) use ($request) {
                    $query->where('store_type', '=', $request->get('platform'));
                });
            } elseif ($request->get('platform') == 2) {
                $orders = $orders->whereHas('store', function ($query) use ($request) {
                    $query->where('store_type', '=', $request->get('platform'));
                });
            } elseif ($request->get('platform') == 3) {
                $orders = $orders->whereHas('store', function ($query) use ($request) {
                    $query->where('store_type', '=', $request->get('platform'));
                });
            }
        }

        $orders = $orders->when($request->billed, function ($query) use ($request) {
            if ($request->billed == 1) {
                return $query->whereNotNull('inv_num');
            } else {
                return $query->whereNull('inv_num');
            }
        });
        $orders = $orders->orderByDesc('created_at');

        $stores = clone $orders;
        $stores = $stores->select('store_id')->groupBy('store_id')->get()->pluck('store_id');

        $all_plans = $stores->map(function ($store) use ($request) {

            return $this->checkStorePlans($store, $request->from, $request->to);
        });
         $total_cod_query=clone  $orders;
        if ($flag) {
            return $orders;

        } else {
            $cod_amount_total = $orders->sum('cod_amount');

            return Datatables::of($orders->with(['store:account_id,name,shipping_charge_in_ra,shipping_charge_out_ra,cod_charge', 'carriers:name,tracking_link']))
                ->addColumn('enable', function ($orders) {
                    return '
                        <input type="checkbox" value="' . $orders->id . '" class="m-group-checkable select" name="select[]">

                   ';
                })->addColumn('sh_date', function ($orders) {
                    if ($orders->shiping_date_time) {
                        return Carbon::parse($orders->shiping_date_time)->diffInHours($orders->created_at) . 'H';

                    }
                    return '';
                })->addColumn('status', function ($order) {
                    if ($order->order_status == 'Delivered') {
                        return '<span class="label label-lg font-weight-bold label-light-success label-inline">' . $order->order_status . '</span>';
                    } elseif ($order->order_status == 'Returned') {
                        return '<span class="label label-lg font-weight-bold label-light-danger label-inline">' . $order->order_status . '</span>';
                    } else {
                        return '<span class="label label-lg font-weight-boldlabel-light-primary label-inline">' . $order->order_status . '</span>';
                    }
                })->addColumn('shipping_price', function ($order) use ($request, $all_plans) {
                    $store_plan = [];
                    foreach ($all_plans as $plan) {
                        if ($plan['store_id'] == $order->store_id) {
                            $store_plan = $plan;
                        }
                    }
                    $price = $this->ShippingPrice($order, $request->get('from'), $store_plan['plans'], $store_plan['hasManyPlan'], $store_plan['numberOfStoreOrders']);
                    if (is_array($price)) {
                        return $price['tatal_cost'];
                    }
                    return 0;
                })
                ->addColumn('inv_num', function ($order) {
                    if ($order->statment_id) {

                        return '<a target="_blank" href="' . url("ne-show-statment/$order->statment_id") . '">ISN' . $order->inv_num . '</a>';
                    }
                    return '';
                })->with(['total_cod' => $total_cod_query->where('store_id', '!=', 77)->sum('cod_amount')])
                ->rawColumns(['enable', 'sh_date', 'status', 'cod_total', 'shipping_price', 'inv_num'])
                ->make(true);

        }


    }

    public function orderExportExcel(Request $request)
    {

        $data = [];
        $i = 0;

        $order = $this->OrderReportsData($request, true);

        $stores = clone $order;
        $stores = $stores->select('store_id')->groupBy('store_id')->get()->pluck('store_id');

        $order = $order->with('store')->get();

        if (is_array($request->get('status'))) {
            if (in_array('Returned', $request->get('status'))) {
                $has_return = true;
            } else {
                $has_return = false;
            }
        } else {
            if ($request->get('status') == 'Returned') {
                $has_return = true;
            } else {
                $has_return = false;
            }
        }

        $all_plans = $stores->map(function ($store) use ($request) {
            return $this->checkStorePlans($store, $request->from, $request->to);
        });

        $data = [];
        $i = 0;
        $cods = 0;

        foreach ($order as $or) {
            $store_plan = [];
            foreach ($all_plans as $plan) {
                if ($plan['store_id'] == $or->store_id) {
                    $store_plan = $plan;
                }
            }

            $shippingCharge = $this->ShippingPrice($or, $request->from, $store_plan['plans'], $store_plan['hasManyPlan'], $store_plan['numberOfStoreOrders']);
            $cod_amount = 0;
            if (($or->order_status == 'Returned' || $or->return_date_carrier != null || request('return_cod'))) {
                $cod_amount = 0;
            } else {
                $cod_amount = $or->cod_amount;
            }
            if ($or->store_id == 77) {
                $cod_amount = '0';
            }
            if ($or->store_id == 74) {
                $weight = $or->Qty_Item * 5.75;
            } else {
                if ($or->country == 'SA') {
                    $weight = ceil($or->weight);
                } else {
                    $weight = $or->chargalbeWeight;
                }

            }

            try {
                $shippingCharge = is_array($shippingCharge) ? $shippingCharge['tatal_cost'] : $shippingCharge;

            } catch (\Throwable $t) {
                dd($or);
            }


            $cods = $or->cod_amount + $cods;
            $data[$i]['shipping_number'] = $or->shipping_number;
            $data[$i]['order_number'] = $or->order_number;
            $data[$i]['carrier'] = $or->carrier;
            $data[$i]['tracking_number'] = $or->tracking_number;
            if (isset($or->store->name)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['item_quantity'] = $or->Qty_Item;
            if ($or->cod_amount > 0) {
                $data[$i]['payment_mode'] = 'COD';
            } else {
                $data[$i]['payment_mode'] = 'paid';
            }
            $data[$i]['cod_amount'] = $cod_amount;


            $data[$i]['country'] = $or->country;
            $data[$i]['city'] = $or->city;
            $data[$i]['order_status'] = $or->order_status;

            $data[$i]['weight'] = $weight;
            $data[$i]['shipping_date'] = $or->shipping_date;
            $data[$i]['delivery_date'] = $or->order_status == 'Returned' ? $or->isnaad_return_date : $or->delivery_date;

            if (auth()->user()->hasPermissionTo('isnaarReport_diff') && request('cost')) {
                $data[$i]['chargalbeWeight'] = $or->chargalbeWeight;
                $data[$i]['actulWeight'] = $or->actulWeight;
                if (auth()->user()->hasPermissionTo('carrier_charge_view')) {
                    $data[$i]['carrier_charge'] = $or->carrier_charge;
                }

                if (auth()->user()->hasPermissionTo('isnaarReport_shippingPrice')) {

                    $data[$i]['shipping_price'] = $shippingCharge;
                }

                try {

                    $data[$i]['diff'] = $shippingCharge - $or->carrier_charge;

                } catch (\Throwable $e) {
                    dd($shippingCharge, $or->carrier_charge, 'sdfsdf');
                }


            }
            $data[$i]['inv'] = $or->inv_num;
            $data[$i]['created_at'] = $or->created_at;

            $i++;
        }

        $data[$i + 1]['shipping_number'] = 'total';
        $data[$i + 1]['order_number'] = '';
        $data[$i + 1]['carrier'] = '';
        $data[$i + 1]['tracking_number'] = '';
        $data[$i + 1]['store'] = '';
        $data[$i + 1]['item_quantity'] = '';
        $data[$i + 1]['payment_mode'] = '';
        $data[$i + 1]['cod_amount'] = '';
        $data[$i + 1]['country'] = '';
        $data[$i + 1]['order_status'] = '';
        $data[$i + 1]['weight'] = '';
        $data[$i + 1]['shipping_date'] = '';
        $data[$i + 1]['delivery_date'] = '';
        $data[$i + 1]['created_at'] = '';
        $data[$i + 1]['cod_amount'] = $cods;

        return Excel::download(new IsnaadFinance($data), 'orders.xlsx');
    }


    public function orders_interrupted(Request $request)
    {
        $inter = interrupted_orders::query();
        if ($request->international == 1) {
            $inter->where([['country', '!=', 'SA'], ['issue', 'International Order']]);
        }
        return Datatables::of($inter)
            ->make(true);

    }

    public function checkStorePlans($store_id, $from, $to)
    {

        $numberOfStoreOrders = 0;
        $hasManyPlan = store::where('account_id', '=', $store_id)->first()->hasMultiplePlan;
        $to = Carbon::parse($to)->startOfMonth()->format('Y-m-d');
        $from = Carbon::parse($from)->endOfMonth()->format('Y-m-d');

        $store = store::where('account_id', '=', $store_id)->first();
        if ($hasManyPlan) {

            if ($store->cr_id) {
                $stores_ids = store::select('account_id')->where('cr_id', $store->cr_id)->get()->pluck('account_id')->toArray();
            } else {
                $stores_ids[] = $store->account_id;
            }

            $mini_plan = nstoreplan::where('store_id', $store_id)->where('fromDate', '<', $to)->orderBy('fromDate', 'desc')->get();

            $numberOfStoreOrders = order::whereIn('store_id', $stores_ids)->whereBetween('shipping_date', [$from, $to])->count();

            $hasManyPlan = true;

        } else {
            $mini_plan = masterPlan::where('store_id', $store_id)->orderBy('from_date', 'desc')->get();
        }


        return [
            'hasManyPlan' => $hasManyPlan,
            'plans' => $mini_plan,
            'numberOfStoreOrders' => $numberOfStoreOrders,
            'store_id' => $store_id
        ];
    }

    public function ShippingPrice($order, $from, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {

        $date = new Carbon($from);
        $extraPrice = 0;
        $order_type = $order->carrier == 'MORA' ? '_fr' : '';

        if ($order->carrier == 'Pick' && $order->store_id != 74) {
            return 5;
        }

        if ($order->created_at->format('Y-m') >= '2021-06') {

            $orderWeight = ceil($order->weight);
            if ($order->store_id == 74) {
                $orderWeight = $order->Qty_Item * 5.75;

            }

        } else {

            $orderWeight = $order->weight;
        }


        if ($hasManyPlan) {

            $mini_plan = $mini_plan->where('fromDate', '<', $order->created_at)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

            if ($order->order_status == 'cancelled') {

                return
                    [
                        'tatal_cost' => $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders),
                        'extraweightPrice' => 0
                    ];
            }
            try {
                $allow_wight_sa = $mini_plan->{'allow_wight_sa' . $order_type};

            } catch (\Exception $e) {

                return 0;
            }

            $shipping_charge_in_ra = $mini_plan->{'in_side_ryad' . $order_type};
            $shipping_charge_out_ra = $mini_plan->{'out_side_ryad' . $order_type};

            $add_cost_in_sa = $mini_plan->{'add_cost_in_sa' . $order_type};
            $cod_charge = $mini_plan->{'cod' . $order_type};

            if ($order->country == 'SA') {

                if ($order->city == 'Riyadh') {
                    $price = $shipping_charge_in_ra;
                    if ($orderWeight > $allow_wight_sa) {
                        $price += $add_cost_in_sa * ceil(($orderWeight - $allow_wight_sa));

                    }


                } else {

                    $price = $shipping_charge_out_ra;

                    if ($orderWeight > $allow_wight_sa) {

                        $price += $add_cost_in_sa * ceil(($orderWeight - $allow_wight_sa));

                    }


                }

                if ($order->cod_amount > 0) {

                    if ($cod_charge < 1) {

                        $price = $price + ($order->cod_amount * $cod_charge);
                    } else {

                        $price += $cod_charge;
                    }
                }
                if (($order->order_status == 'Returned' || $order->return_date_carrier != null)) {

                    $price = $price + $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders);
                }


                if ($orderWeight > $allow_wight_sa) {
                    $extraweight = ceil($order->weight) - $allow_wight_sa;
                    $extraPrice = ($extraweight * $mini_plan->extra_wight_ksa);
                    $price = $price + $extraPrice;
                }

            } else {

                return $price = $this->InternationalShpping($order, $mini_plan);
            }
        } else {

            $mini_plan = $mini_plan->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();

            if ($order->order_status == 'cancelled') {

                return
                    [
                        'tatal_cost' => $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders),
                        'extraweightPrice' => 0
                    ];

            }
            try {
                $allow_wight_sa = $mini_plan->{'allowed_weight_in_sa' . $order_type};
            } catch (\Exception $e) {

                return
                    [
                        'tatal_cost' => 0,
                        'extraweightPrice' => 0
                    ];
            }
            $add_cost_in_sa = $mini_plan->{'add_cost_in_sa' . $order_type};
            $cod_charge = $mini_plan->{'cod_charge' . $order_type};

            if ($order->country == 'SA') {
                if ($order->city == 'Riyadh') {
                    $shipping_charge_in_ra = $mini_plan->{'in_side_ryad' . $order_type};

                    $price = $shipping_charge_in_ra;

                    if ($orderWeight > $allow_wight_sa) {

                        //  dd($orderWeight , $allow_wight_sa,$order,$price);
                        $extraweight = $orderWeight - $allow_wight_sa;
                        $extraPrice = ($extraweight * $add_cost_in_sa);
                        // dd($extraPrice);
                        $price = $price + $extraPrice;
                    }


                    if ($order->cod_amount > 0) {

                        if ($order->store_id == 66) {

                            if ($order->cod_amount < 500 && $order->cod_amount > 0) {
                                $price += 7;
                            } elseif ($order->cod_amount < 1000 && $order->cod_amount > 500) {
                                $price += 12;
                            } elseif ($order->cod_amount > 1000) {
                                $price += $order->cod_amount * .018;
                            }
                        } else {
                            if ($order->cod_amount > 0) {
                                if ($cod_charge < 1) {
                                    $price = $price + ($order->cod_amount * $cod_charge);
                                } else {
                                    $price += $cod_charge;
                                }
                            }

                        }

                    }

                    if (($order->order_status == 'Returned' || $order->return_date_carrier != null)) {

                        $price = $price + $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders);
                    }


                } else {

                    $shipping_charge_out_ra = $mini_plan->{'out_side_ryad' . $order_type};

                    $price = $shipping_charge_out_ra;
                    if ($orderWeight > $allow_wight_sa) {
                        $extraweight = ceil($order->weight) - $allow_wight_sa;
                        $extraPrice = ($extraweight * $add_cost_in_sa);
                        $price = $price + $extraPrice;
                    }
                    if (($order->order_status != 'Returned' || $order->return_date_carrier != null)) {
                        if ($order->store_id == 66) {
                            if ($order->cod_amount < 500 && $order->cod_amount > 0) {
                                $price += 7;
                            } elseif ($order->cod_amount < 1000 && $order->cod_amount > 500) {
                                $price += 12;
                            } elseif ($order->cod_amount > 1000) {
                                $price += $order->cod_amount * .018;
                            }
                        } else {
                            if ($order->cod_amount > 0) {
                                if ($cod_charge < 1) {
                                    $price = $price + ($order->cod_amount * $cod_charge);

                                } else {
                                    try {
                                        $price += $mini_plan->cod_charge;
                                    } catch (\Throwable $e) {
                                        dd('dfssdf');
                                    }

                                }
                            }
                        }
                    } else {
                        return
                            [
                                'tatal_cost' => $price + $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders),
                                'extraweightPrice' => 0
                            ];

                    }

                }
            } else {

                return $this->InternationalShpping($order, $mini_plan);


            }
        }

        return
            [
                'tatal_cost' => $price,
                'extraweightPrice' => 0
            ];


    }


    public function InternationalShpping($order, $mini_plan, $flag = true)
    {

        $dis = $order->shipping_date > '2021-09-30' ? .15 : 0;

        if (!$flag) {

            $order = $order->order;
        }
        if ($order->created_at->format('Y-m') >= '2021-06') {

            $orderWeight = ceil($order->chargalbeWeight * 2) / 2;

        } else {

            $orderWeight = $order->weight;
        }


        if ($order->countries->is_gcc) {

            $price = $order->shipping_date > '2021-09-30' ? $mini_plan->GCC : 35;
            $bounus = $order->shipping_date > '2021-09-30' ? $mini_plan->add_cost_out_sa : 8;

            if ($orderWeight > $mini_plan->allow_wight_gcc) {

                $extraweight = $orderWeight - $mini_plan->allow_wight_gcc;


                $extraPrice = ($extraweight / .5) * $bounus;


                if ($flag) {

                    $lastPrice = $extraPrice + $price;

                    return [
                        'tatal_cost' => $lastPrice + ($lastPrice * $dis),
                        'extraweightPrice' => $extraPrice
                    ];
                } else {

                    $lastPrice = $extraPrice + $price;
                    return $lastPrice + ($lastPrice * $dis);

                }


            } else {

                return [
                    'tatal_cost' => $price,
                    'extraweightPrice' => 0
                ];


            }

        } else {

            $last_price = $order->shipping_date > '2021-09-30' ? $order->countries->first_half_october : $order->countries->first_half;
            $anotherHalf = $order->shipping_date > '2021-09-30' ? $order->countries->each_aditional_afte_half_october : $order->countries->each_aditional_afte_half;

            if ($orderWeight < .5) {

                return [
                    'tatal_cost' => round($last_price + ($last_price * $dis), 2),
                    'extraweightPrice' => 0
                ];


            } else {

                if ($orderWeight > .5) {
                    $extraweight = $orderWeight - .5;
                    $extraPrice = ($extraweight / .5) * $anotherHalf;

                    if ($flag) {

                        $lastPrice = $extraPrice + $last_price;

                        return [
                            'tatal_cost' => $lastPrice + ($lastPrice * $dis),
                            'extraweightPrice' => $extraPrice
                        ];
                    } else {

                        $lastPrice = $extraPrice + $last_price;
                        return $lastPrice + ($lastPrice * $dis);

                    }
                }


            }

            if ($flag) {

                return [
                    'tatal_cost' => $last_price + ($dis * $last_price),
                    'extraweightPrice' => 0
                ];
            }

            return round($last_price + ($last_price * $dis), 2);
        }

    }

    public function getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {

        $order_type = $order->carrier == 'MORA' ? '_fr' : '';

        if ($hasManyPlan) {

            if ($order->city == 'Riyadh') {
                try {
                    if ($order->cod_amount > 0 && $order->created_at >= '2021-10-01') {
                        $codBouns = $mini_plan->cod < 1 ? ($mini_plan->cod * $order->cod_amount) : $mini_plan->cod;

                        return $price = $mini_plan->{'return_charge_in' . $order_type} - $codBouns;
                    } else {
                        return $price = $mini_plan->{'return_charge_in' . $order_type};
                    }
                } catch (\Throwable $t) {
                    dd($mini_plan, $order);
                }


            } else {
                try {
                    if ($order->cod_amount > 0 && $order->created_at >= '2021-10-01') {
                        $codBouns = $mini_plan->cod < 1 ? ($mini_plan->cod * $order->cod_amount) : $mini_plan->cod;
                        return $price = $mini_plan->{'return_charge_out' . $order_type} - $codBouns;
                    } else {
                        $price = $mini_plan->{'return_charge_out' . $order_type};
                    }
                } catch (\Throwable $t) {
                    dd($order);
                }
            }


            return $price;
        } else {

            //  $mini_plan=$mini_plan->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();

            $cod_charge = (float)$mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type}; ///if cod < 0 cod chagre = cod *cod chagre


            //  dd($order);

            $cod_charge = (float)$mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type}; ///if cod < 0 cod chagre = cod *cod chagre
            try {
                if ($order->city == 'Riyadh') {

                    if ($order->cod_amount > 0 && Carbon::parse($order->shipping_date)->format('Y-m-d') > '2021-09-30') {

                        $price = $mini_plan->{'return_charge_in' . $order_type} - $cod_charge;
                    } else {

                        $price = $mini_plan->{'return_charge_in' . $order_type};

                    }

                } else {

                    if ($order->cod_amount > 0 && Carbon::parse($order->shipping_date)->format('Y-m-d') > '2021-09-30') {

                        $price = $mini_plan->{'return_charge_out' . $order_type} - $cod_charge;
                    } else {

                        $price = $mini_plan->{'return_charge_out' . $order_type};

                    }

                }
            } catch (\Throwable $t) {
                dd('hello mother fucker');
            }


            if ($order->Qty_Item == 1) {
                return $price;
            } else {

                if (count(explode(',', $mini_plan->{'return_charge_each_extra' . $order_type})) == 1) {

                    $extraQty = $order->Qty_Item - 1;

                    $extraPrice = $mini_plan->{'return_charge_each_extra' . $order_type} * $extraQty;

                    return $price + $extraPrice;
                } else {
                    $each_2nd = explode(',', $mini_plan->{'return_charge_each_extra' . $order_type});

                    if ($each_2nd[1] == 3 && $order->Qty_Item > 2) {

                        $extraQty = $order->Qty_Item - 2;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;
                        return $price + $extraPrice;
                    } elseif ($each_2nd[1] == 5 && $order->Qty_Item > 4) {
                        $extraQty = $order->Qty_Item - 4;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;
                        return $price + $extraPrice;
                    } elseif ($each_2nd[1] == 4 && $order->Qty_Item > 3) {
                        $extraQty = $order->Qty_Item - 3;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;
                        return $price + $extraPrice;
                    } else {
                        return $price;
                    }
                }
            }
        }

    }

    public function tranferToBilling(Request $request)
    {
        $store = store::query()->where('account_id', $request->store)->first();
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $statment = statment::query()->where('description_from_date', $from->format('Y-m-d'))->where('description_to_date', $to->format('Y-m-d'))->first();

        if (!$statment) {
            return response()->json([
                'success' => false,
                'message' => 'there is no bill for this period',
            ]);
        }
        $order = $this->OrderReportsData($request, true);


        $order = $order->with('store')->get();
        if (is_array($request->get('status'))) {
            if (in_array('Returned', $request->get('status'))) {
                $has_return = true;
            } else {
                $has_return = false;
            }
        } else {
            if ($request->get('status') == 'Returned') {
                $has_return = true;
            } else {
                $has_return = false;
            }
        }


        $data = [];
        $i = 0;
        $cods = 0;

        foreach ($order as $or) {


            if (($or->order_status == 'Returned' || $or->return_date_carrier != null || request('return_cod'))) {
                $cod_amount = 0;
            } else {
                $cod_amount = $or->cod_amount;
            }
            if ($or->store_id == 77) {
                $cod_amount = '0';
            }
            if ($or->store_id == 74) {
                $weight = $or->Qty_Item * 5.75;
            } else {
                if ($or->country == 'SA') {
                    $weight = ceil($or->weight);
                } else {
                    $weight = $or->chargalbeWeight;
                }

            }


            $cods = $or->cod_amount + $cods;
            $data[$i]['shipping_number'] = $or->shipping_number;
            $data[$i]['order_number'] = $or->order_number;
            $data[$i]['carrier'] = $or->carrier;
            $data[$i]['tracking_number'] = $or->tracking_number;
            if (isset($or->store->name)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['item_quantity'] = $or->Qty_Item;
            if ($or->cod_amount > 0) {
                $data[$i]['payment_mode'] = 'COD';
            } else {
                $data[$i]['payment_mode'] = 'paid';
            }
            $data[$i]['cod_amount'] = $cod_amount;


            $data[$i]['country'] = $or->country;
            $data[$i]['city'] = $or->city;
            $data[$i]['order_status'] = $or->order_status;

            $data[$i]['weight'] = $weight;
            $data[$i]['shipping_date'] = $or->shipping_date;
            $data[$i]['delivery_date'] = $or->order_status == 'Returned' ? $or->isnaad_return_date : $or->delivery_date;

            $data[$i]['inv'] = $or->inv_num;
            $data[$i]['created_at'] = $or->created_at;

            $i++;
        }

        $data[$i + 1]['shipping_number'] = 'total';
        $data[$i + 1]['order_number'] = '';
        $data[$i + 1]['carrier'] = '';
        $data[$i + 1]['tracking_number'] = '';
        $data[$i + 1]['store'] = '';
        $data[$i + 1]['item_quantity'] = '';
        $data[$i + 1]['payment_mode'] = '';
        $data[$i + 1]['cod_amount'] = '';
        $data[$i + 1]['country'] = '';
        $data[$i + 1]['order_status'] = '';
        $data[$i + 1]['weight'] = '';
        $data[$i + 1]['shipping_date'] = '';
        $data[$i + 1]['delivery_date'] = '';
        $data[$i + 1]['created_at'] = '';
        $data[$i + 1]['cod_amount'] = $cods;
        $summary = $this->getCodReportSummary($order, $store, $cods, $statment);
        $first_day = $from->day;
        $last_day = $to->day;
        $month = $to->format('F Y');

        $real_name = "$store->name COD Report ($first_day To $last_day $month)." . 'xlsx';
        $store_Name = time() . rand(100, 999) . '.' . 'xlsx';
        statment_file::create(['statment_id' => $statment->id, 'store_name' => $store_Name, 'real_name' => $real_name]);
        Excel::store(new FinanceCodReport($data, $summary), $store_Name, 'statment');
        return response()->json([
            'success' => true,
            'message' => 'exported successfully'
        ]);

    }

    public function getCodReportSummary($orders, $store, $cod_summation, $statment)
    {
        $cod_count = $orders->filter(function ($order) {
            return $order->cod_amount > 0;
        })->count();
        $paid_count = $orders->filter(function ($order) {
            return $order->cod_amount == 0;
        })->count();
        $net = $cod_summation - $statment->getOriginal('total_amount');
        $data = [];
        $data[] = [
            $store->name,
            'COD',
            $cod_count,
            $cod_summation
        ];
        $data[] = [
            $store->name,
            'Paid',
            $paid_count,
            '0'
        ];
        $data[] = [
            'Grand Total',
            '',
            $cod_count + $paid_count,
            $cod_summation
        ];
        $data[] = [
            'Rounded differences COD & International  tax',
            '',
            '0',

            '0'
        ];
        $data[] = [
            'Net COD',
            '',
            $cod_count + $paid_count,
            $cod_summation
        ];
        $data[] = [
            'Net Amount for ISNAAD Services',
            '',
            '',
            $net
        ];
        $data[] = [
            'After Deduting ISNAAD Services Amount will be Balance',
            '',
            '',
            $statment->getOriginal('total_amount')
        ];

        return $data;

    }

    public function check_statment(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $statment = statment::query()->where('description_from_date', $from->format('Y-m-d'))->where('description_to_date', $to->format('Y-m-d'))->first();

        if (!$statment) {
            return response()->json([
                'success' => false,
                'message' => 'there is no bill for this period',
            ]);
        }
        return response()->json([
            'success' => true,
            'statment' => $statment
        ]);
    }

}
