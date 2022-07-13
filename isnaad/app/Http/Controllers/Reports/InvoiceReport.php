<?php

namespace App\Http\Controllers\Reports;

use App\Models\international_return;
use App\Models\invoice_discount;
use App\Models\invoice_extra_cost;
use App\Models\storage;
use App\Models\transportation_cost;
use DateInterval;
use App\carrier;
use App\Models\nstoreplan;
use App\Exports\HandlingPick;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\isnaad_return;
use App\Models\masterPlan;
use App\Models\replenishment;
use App\Models\store_plane;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use App\Exports\OrderExportInoiceReport;


class InvoiceReportMaster extends Controller
{
    public static $store = null;
    public static $count = null;

    public function index()
    {
        $stores = store::all();
        $array = [
            'stores' => $stores
        ];
        return view('m_design.Reports.newInovice_report', $array);
    }

    public function InvoiceReportDate(Request $request, $flag = false)
    {


        $date = new Carbon($request->from);
        $month = $date->month;

        $orders = order::query();

        $mini_plans = $this->checkStorePlans($request);

        if ($mini_plans['hasManyPlan']) {

            //   $mini_plan= $mini_plans['plans']->where('from_num','<=',$mini_plans['numberOfStoreOrders'])->where('to_num','>=',$mini_plans['numberOfStoreOrders'])->first();
            $mini_plan = $mini_plans['plans'];

        } else {
            $mini_plan = $mini_plans['plans'];

        }

        $orders = $orders->select('order_status', 'chargalbeWeight', 'shipping_number', 'carrier_charge', 'carrier', 'weight', 'tracking_number', 'cod_amount', 'store_id', 'Qty_Item', 'country', 'city', 'shipping_date', 'order_number', 'created_at')
            ->where('carrier', '!=', 'Shipox')->with(['countries', 'store:id,account_id,name,allow_wight_gcc'])->Active();
        $orders = $this->filter($orders, $request);


        ///////from replanchment
        if ($request->serviceType == 4) {
            //    dd('dfs');
            $date = new Carbon($request->from);
            $month = $date->month;

            $rep = replenishment::query();
            if ($request->has('store') && $request->get('store') != '') {
                $rep = $rep->whereHas('store', function ($query) use ($request) {
                    $query->where('account_id', '=', $request->get('store'));
                });
                if ($request->has('from') && $request->get('from') != '') {
                    $date = new Carbon($request->from);
                    if ($request->has('to') && $request->get('to') != '') {
                        $to = new \DateTime($request->get('to'));
                        $to = $to->format('y/m/d');
                        $from = new \DateTime($request->get('from'));
                        $from = $from->format('y/m/d');
                        $orders = $rep->whereBetween('date', [$from, $to]);
                    } else {
                        $from = new \DateTime($request->get('from'));
                        $from = $from->format('y/m/d');
                        $to = Carbon::now();
                        $to->format('y/m/d');
                        $to = $to->toDateString();
                        $orders = $rep->whereBetween('date', [$from, $to]);
                    }
                }
            }
            return Datatables::of($rep->with([
                'store' => function ($re) use ($request, $month) {
                    $re->withCount(['orders as count_per_month' => function ($query) use ($month) {
                        $query->whereMonth('created_at', $month);
                    }]);
                }
            ], 'store.hasPlan'))
                ->addColumn('cost', function ($rep) use ($request, $mini_plans) {
                    return $this->getReplanchmentCost($rep, $request);
                })->rawColumns(['cost'])
                ->make(true);
        } elseif ($request->serviceType == 5) {
            $store = store::query();
            if ($request->has('store') && $request->get('store') != '') {
                $orders = $store->where('account_id', $request->get('store'));

            }
            return Datatables::of($store->with('hasPlan'))
                ->addColumn('cost', function ($s) use ($request) {
                    //   dd('sdf');
                    return $this->getSystemFee($s, $request);
                })
                ->addColumn('serviceType', function ($store) use ($request) {
                    //
                    return 'Fee System price';
                })->rawColumns(['cost', 'serviceType'])
                ->addColumn('ID', function ($store) use ($request) {
                    //   dd('sdf');
                    return 'Fee';
                })->rawColumns(['cost', 'serviceType', 'ID'])
                ->make(true);
        } elseif ($request->serviceType == 6) {

            $date = $request->from ? Carbon::create($request->from) : new Carbon();
            $month = $date->month;

            $orders = order::query()->where([['carrier', '!=', 'Shipox'], ['order_status', 'Returned']])->whereNotNull('isnaad_return_date')->with(['store.hasPlan', 'store' => function ($q) use ($month) {
                $q->withCount(['orders as count_per_month' => function ($query) use ($month) {
                    $query->whereMonth('created_at', $month);
                }]);
            }])->Active()->where('city', 'Riyadh');
            if ($request->has('from') && $request->get('from') != '') {
                $date = new Carbon($request->from);


                if ($request->has('to') && $request->get('to') != '') {

                    $to = new \DateTime($request->get('to'));
                    $to = $to->format('y/m/d');
                    $from = new \DateTime($request->get('from'));
                    $from = $from->format('y/m/d');
                    $orders = $orders->whereBetween('isnaad_return_date', [$from, $to]);
                } else {
                    $from = new \DateTime($request->get('from'));
                    $from = $from->format('y/m/d');

                    $to = Carbon::now();
                    $to->format('y/m/d');
                    $to = $to->toDateString();
                    $orders = $orders->whereBetween('isnaad_return_date', [$from, $to]);

                }
            }
            if ($request->has('store') && $request->get('store') != '') {
                //  dd('sfd');
                $orders = $orders->whereHas('store', function ($query) use ($request) {
                    $query->where('account_id', '=', $request->get('store'));
                });

            }

            return Datatables::of($orders)
                ->addColumn('cost', function ($orders) use ($request, $mini_plan, $mini_plans) {

                    return $this->getReturnCharge($orders, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
                })->addColumn('serviceType', function ($orders) use ($request) {

                    return 'Return:Handling: Pick & Pack Services';


                })
                ->rawColumns(['cost', 'serviceType'])
                ->make(true);
        } elseif ($request->serviceType == 7) {

            $orders = isnaad_return::query()->with('order', 'store', 'carrier');
            if ($request->has('from') && $request->get('from') != '') {
                $date = new Carbon($request->from);


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
            if ($request->has('store') && $request->get('store') != '') {
                //  dd('sfd');
                $orders = $orders->whereHas('store', function ($query) use ($request) {

                    $query->where('account_id', '=', $request->get('store'));
                });

            }

            return Datatables::of($orders)
                ->addColumn('cost', function ($orders) use ($request, $mini_plan, $mini_plans) {
                    return $this->getIsnaadReturnCost($orders, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
                })->addColumn('serviceType', function ($orders) use ($request) {
                    return 'Shipping: Client Return - Carrier & Transportation';

                })
                ->rawColumns(['cost', 'serviceType'])
                ->make(true);


        } elseif ($request->serviceType == 0) {

            if ($flag) {
                return $orders;
            }

            return Datatables::of($orders)
                ->addColumn('cost', function ($orders) use ($request, $mini_plan, $mini_plans) {

                    $price = $this->hasPlan($orders, $request, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);

                    if ($price > 0) {
                        return $price;
                    }

                    return $this->getCostForHandilingAndPick($orders, $request, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);


                })->addColumn('serviceType', function ($orders) use ($request) {

                    return 'Handling: Pick & Pack Services';

                })
                ->rawColumns(['cost', 'serviceType'])
                ->make(true);
        } else if ($request->serviceType == 8) {
            $orders = international_return::query()->with(['order.countries', 'carrier', 'store'])->where('active', 1);
            if ($request->has('from') && $request->get('from') != '') {
                $date = new Carbon($request->from);


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
            return Datatables::of($orders)
                ->addColumn('cost', function ($orders) use ($request, $mini_plan, $mini_plans) {
                    return $this->international_return($orders);
                })->addColumn('serviceType', function () {
                    return 'international  Return - Carrier & Transportation';
                })
                ->rawColumns(['cost', 'serviceType'])
                ->make(true);
        } elseif ($request->serviceType == 9) {
            $to = Carbon::parse($request->to)->addDay();

            $stoage = storage::where('store_id', $request->store)->whereBetween('date', [$request->from, $to]);

            return Datatables::of($stoage->with('store'))
                ->addColumn('type', function ($stoage) {
                    return $stoage->storage_type;
                })->addColumn('serviceType', function () {
                    return 'Storage: Shelving and warehousing';
                })->make(true);
        } elseif ($request->serviceType == 10) {

            $to = Carbon::parse($request->to)->addDay();

            $transportation = transportation_cost::where('store_id', $request->store)->whereBetween('date', [$request->from, $to]);

            return Datatables::of($transportation->with('store'))
                ->addColumn('serviceType', function () {
                    return 'Isnaad Transportaion';
                })->make(true);
        } elseif ($flag) {
            return $orders;

        } else {

            return Datatables::of($orders)
                ->addColumn('cost', function ($orders) use ($request, $mini_plan, $mini_plans) {

                    if ($request->serviceType == 0) {

                        $price = $this->hasPlan($orders, $request, $mini_plans, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
                        if ($price > 0) {
                            return $price;
                        }
                        //      dd($this->getCostForHandilingAndPick($orders, $request));

                        return $this->getCostForHandilingAndPick($orders, $request, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
                    } else {


                        $prcie = $this->ShippingPrice($orders, $request->from, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);//fourth parameter true when store has many plans

                        return $prcie['tatal_cost'];

                    }


                })
                ->addColumn('serviceType', function ($orders) use ($request) {
                    if ($request->serviceType == 0) {
                        return 'Handling: Pick & Pack Services';
                    }
                    return 'Shipping: Carrier & Transportation';
                })->rawColumns(['cost', 'serviceType'])
                ->make(true);
        }

    }

    public function orderExportExcel(Request $request, $flag = true)
    {
        $mini_plans = $this->checkStorePlans($request);


        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;

        $order = $this->InvoiceReportDate($request, true);

        $order = $order->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        foreach ($order as $or) {
            $cost = $this->ShippingPrice($or, $request->from, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);

            $extraWeightPrice = $cost['extraweightPrice'];
            $total_cost = $cost['tatal_cost'];

            if (isset($or->store)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $or->shipping_date;
            $data[$i]['total_item'] = $this->totalItem($or->description);
            $data[$i]['weight'] = $or->country == 'SA' ? ceil($or->weight) : $or->chargalbeWeight;
            $data[$i]['Total_Qty'] = $or->Qty_Item;
            $data[$i]['Service_Type'] = $this->getServiceType(2);
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'SHiPPING: Order#:' . $or->order_number . '-Carrier>' . $or->carrier . ':' . $or->tracking_number;

            if ($flag) {
                if ($or->country == 'SA') {
                    $data[$i]['country'] = 'SA';
                } else {
                    $data[$i]['country'] = $or->countries->is_gcc ? 'GCC' : 'international';
                }

                if ($or->city == 'Riyadh') {
                    $data[$i]['city'] = 'in Riyadh';
                } else {
                    $data[$i]['city'] = 'out Riyadh';
                }

                $data[$i]['cod'] = $or->cod_amount;
                $data[$i]['carrier_charge'] = $or->carrier_charge;
                //  dd($total_cost,$or->carrier_charge);
                try {
                    $data[$i]['diff'] = $total_cost - $or->carrier_charge;

                } catch (\Throwable $e) {
                    dd($total_cost, $or->carrier_charge, $or, $total_cost);
                }

            } else {
                if ($or->country == 'SA') {
                    $data[$i]['country'] = 'SA';
                } else {
                    $data[$i]['country'] = $or->countries->is_gcc ? 'GCC' : $or->country;
                }

                if ($or->city == 'Riyadh') {
                    $data[$i]['city'] = 'in Riyadh';
                } else {
                    $data[$i]['city'] = 'out Riyadh';
                }
            }
            $data[$i]['extraWeightPrice'] = $extraWeightPrice;
            $data[$i]['Cost_Value'] = $total_cost;
            $data[$i]['tracking_number'] = $or->tracking_number;
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, true), 'orders.xlsx') : $data;
    }

    public function HandlingPickExport(Request $request, $flag = true)
    {

        $mini_plans = $this->checkStorePlans($request);

        if ($mini_plans['hasManyPlan']) {

            $mini_plan = $mini_plans['plans']->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->first();

        } else {
            $mini_plan = $mini_plans['plans'];

        }

        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;
        $order = $this->InvoiceReportDate($request, true);
        $order = $order->get();
        // dd($order[1]);
        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);
        foreach ($order as $or) {
            if (isset($or->store->name)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }

            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $or->shipping_date;
            $data[$i]['total_item'] = $this->totalItem($or->description);
            $data[$i]['weight'] = $or->country == 'SA' ? ceil($or->weight) : $or->chargalbeWeight;
            $data[$i]['Total_Qty'] = $or->Qty_Item;
            $data[$i]['Service_Type'] = $request->serviceType;
            $data[$i]['Service_Type'] = $this->getServiceType(0);
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'Warehouse Order Processing and Packaging';
            $data[$i]['country'] = $or->country == 'SA' ? $or->country : ($or->countries->is_gcc == 1 ? 'gcc' : $or->country);
            $data[$i]['city'] = $or->city == 'Riyadh' ? 'in Riyadh ' : 'out Riyadh';
            $data[$i]['extraWeightPrice'] = 0;

            $data[$i]['Cost_Value'] = $this->getCostForHandilingAndPick($or, $request, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);

            $data[$i]['tracking_number'] = $or->tracking_number;

            $i++;
        }

        return $flag == true ? Excel::download(new HandlingPick($data), 'orders.xlsx') : $data;
    }

    public function getServiceType($type)
    {
        if ($type == 0) {
            return 'Handling: Pick & Pack Services';
        } else {
            return 'Shipping: Carrier & Transportation';
        }
    }

    public function totalItem($description)
    {

        return count(explode(',', $description));
    }

    public function getCostForHandilingAndPick($order, $request, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {

        $order_type = $order->carrier == 'MORA' || $order->carrier == 'Jones' ? '_fr' : '';
        $price = $this->hasPlan($order, $request, $mini_plan, $hasManyPlan, $numberOfStoreOrders);
        if ($price > 0) {
            return $price;
        }

        $mini_plan = $mini_plan->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();

        try {
            $price = $mini_plan->{'processing_charge' . $order_type};
        } catch (\Exception $e) {

            dd($mini_plan, $order);
        }


        $anotherQty = explode(',', $mini_plan->{'each_2nd_units' . $order_type});
        //  dd($anotherQty,$price);
        if ($order->Qty_Item == 1) {
            return $price + $mini_plan->{'isnaad_packaging' . $order_type};
        } else {

            if (count(explode(',', $mini_plan->{'each_2nd_units' . $order_type})) == 1) {///when  each_2nd_units not has comma
                $extraQty = $order->{'Qty_Item' . $order_type} - 1;
                $extraPrice = $mini_plan->{'each_2nd_units' . $order_type} * $extraQty;

                return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
            } else {
                $each_2nd = explode(',', $mini_plan->{'each_2nd_units' . $order_type});

                if ($each_2nd[1] == 3 && $order->Qty_Item > 2) {

                    $extraQty = $order->Qty_Item - 2;
                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
                } elseif ($each_2nd[1] == 5 && $order->Qty_Item > 4) {
                    $extraQty = $order->Qty_Item - 4;
                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
                } elseif ($each_2nd[1] == 4 && $order->Qty_Item > 3) {
                    // dd('sdf');

                    $extraQty = $order->Qty_Item - 3;
                    //  dd( $extraQty);
                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
                } elseif ($each_2nd[1] == 1 && $order->Qty_Item > 1) {
                    // dd('sdf');

                    $extraQty = $order->Qty_Item - 1;
                    //  dd( $extraQty);
                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
                } else {
                    return $mini_plan->{'isnaad_packaging' . $order_type} + $mini_plan->{'processing_charge' . $order_type};
                }
            }

        }
    }

    public function InvoiceExportDispatecher(Request $request)
    {

        if ($request->serviceType == 0) {

            return $this->HandlingPickExport($request);
        } elseif ($request->serviceType == 2) {

            return $this->orderExportExcel($request);
        } elseif ($request->serviceType == 3) {
            return $this->ExportAll($request);
        } elseif ($request->serviceType == 4) {
            return $this->exportReplanchment($request, true);
        } elseif ($request->serviceType == 5) {
            return $this->exportSystemFee($request, true);
        } elseif ($request->serviceType == 6) {
            return $this->exportReturnCharge($request, true);
        } elseif ($request->serviceType == 7) {
            return $this->exportIsnaadReturn($request, true);
        } elseif ($request->serviceType == 9) {
            return $this->StorageExport($request, true);
        } elseif ($request->serviceType == 8) {
            return $this->exportInternationalReturn($request, true);
        } elseif ($request->serviceType == 10) {
            return $this->TransportaionExport($request, true);
        }
    }

    public function ExportAll($request)
    {
        $dataHandling = $this->HandlingPickExport($request, false);
        $dataOrder = $this->orderExportExcel($request, false);
        $dataRetrun = $this->exportReturnCharge($request, false);
        $data = array_merge($dataOrder, $dataHandling);
        $data = array_merge($dataRetrun, $data);
        $data = array_merge($data, $this->exportReplanchment($request, false));
        $data = array_merge($data, $this->exportSystemFee($request, false));
        $data = array_merge($data, $this->exportIsnaadReturn($request, false));
        $data = array_merge($data, $this->StorageExport($request, false));
        $data = array_merge($data, $this->exportInternationalReturn($request, false));
        $data = array_merge($data, $this->TransportaionExport($request, false));
        $data = array_merge($data, $this->ExtraCostExport($request, false));
        $data = array_merge($data, $this->DiscountExport($request, false));
        $sum = 0;

        foreach ($data as $_data) {
            try {
                $sum += $_data['Cost_Value'];
            } catch (\Exception $e) {
                dd($_data);
            }

        }
        $index = count($data) + 1;
        $data[$index]['store'] = 'total';
        $data[$index]['statment_name'] = '';
        $data[$index]['date'] = '';
        $data[$index]['total_item'] = '';
        $data[$index]['total_Sku'] = '';
        $data[$index]['Total_Qty'] = '';
        $data[$index]['Service_Type'] = '';
        $data[$index]['ID_Reg'] = '';
        $data[$index]['sad'] = '';
        $data[$index]['country'] = '';
        $data[$index]['city'] = '';
        $data[$index]['extraWeightPrice'] = 0;
        $data[$index]['Cost_Value'] = $sum;
        $data[$index]['tracking_number'] = '';
        return Excel::download(new HandlingPick($data), 'orders.xlsx');
    }

    public function hasPlan($orders, $request, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {
        $order_type = $orders->carrier == 'MORA' || $orders->carrier == 'Jones' ? '_fr' : '';

        if ($hasManyPlan) {

            $mini_plan = $mini_plan->where('fromDate', '<', $orders->shipping_date)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

            $price = $mini_plan->{'processing_charge' . $order_type};

            $anotherQty = explode(',', $mini_plan->{'each_2nd_units' . $order_type});

            if ($orders->Qty_Item == 1) {

                return $price + $mini_plan->{'isnaad_packaging' . $order_type};
            } else {

                if (count(explode(',', $mini_plan->{'each_2nd_units' . $order_type})) == 1) {///when  each_2nd_units not has comma

                    $extraQty = $orders->Qty_Item - 1;

                    $extraPrice = $mini_plan->{'each_2nd_units' . $order_type} * $extraQty;

                    return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;

                } else {
                    $each_2nd = explode(',', $mini_plan->{'each_2nd_units' . $order_type});

                    if ($each_2nd[1] == 3) {

                        $extraQty = $orders->Qty_Item - 2;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;
                        return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
                    } elseif ($each_2nd[1] == 5) {
                        $extraQty = $orders->Qty_Item - 4;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;
                        return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice;
                    }
                }

            }

        } else {
            return 0;
        }
    }

    public function ShippingPrice($order, $from, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {

        $date = new Carbon($from);
        $extraPrice = 0;
        $order_type = $order->carrier == 'MORA' || $order->carrier == 'Jones' ? '_fr' : '';

        if ($order->order_status == 'cancelled') {

            return [
                'tatal_cost' => $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders),
                'extraweightPrice' => 0
            ];
        }
        if ($order->carrier == 'Pick') {
            return [
                'tatal_cost' => 5,
                'extraweightPrice' => 0
            ];
        }

        if ($order->created_at->format('Y-m') >= '2021-06') {

            $orderWeight = ceil($order->weight);

        } else {

            $orderWeight = $order->weight;
        }

        if ($hasManyPlan) {

            $mini_plan = $mini_plan->where('fromDate', '<', $order->created_at)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

            $allow_wight_sa = $mini_plan->{'allow_wight_sa' . $order_type};
            $shipping_charge_in_ra = $mini_plan->{'in_side_ryad' . $order_type};
            $shipping_charge_out_ra = $mini_plan->{'out_side_ryad' . $order_type};
            // dd($shipping_charge_in_ra,$mini_plan);
            $add_cost_in_sa = $mini_plan->{'add_cost_in_sa' . $order_type};
            $cod_charge = $mini_plan->{'cod' . $order_type};

            if ($order->country == 'SA') {

                if ($order->city == 'Riyadh') {
                    $price = $shipping_charge_in_ra;
                    if ($order->weight > $allow_wight_sa) {
                        $price += $add_cost_in_sa * ceil(($order->weight - $allow_wight_sa));

                    }


                } else {
                    $price = $shipping_charge_out_ra;
                    if ($order->weight > $allow_wight_sa) {
                        $price += $add_cost_in_sa * ceil(($order->weight - $allow_wight_sa));
                    }


                }
                if ($order->cod_amount > 0) {

                    if ($cod_charge < 1) {

                        $price = $price + ($order->cod_amount * $cod_charge);
                    } else {

                        $price += $cod_charge;
                    }
                }


                if ($orderWeight > $allow_wight_sa) {
                    $extraweight = ceil($order->weight) - $allow_wight_sa;
                    $extraPrice = ($extraweight * $mini_plan->extra_wight_ksa);
                    $price = $price + $extraPrice;
                }

            } else {

                return $price = $this->InternationalShpping($order);
            }
        } else {

            $mini_plan = $mini_plan->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();
            //   dd($mini_plan);
            $allow_wight_sa = $mini_plan->{'allowed_weight_in_sa' . $order_type};

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

                } else {
                    //  dd($mini_plan);
                    // $mini_plan = $mini_plan->where('from_date', '<=', $order->created_at->format('Y-m-d'))->first();

                    $shipping_charge_out_ra = $mini_plan->{'out_side_ryad' . $order_type};

                    $price = $shipping_charge_out_ra;
                    if ($orderWeight > $allow_wight_sa) {
                        $extraweight = ceil($order->weight) - $allow_wight_sa;
                        $extraPrice = ($extraweight * $add_cost_in_sa);
                        $price = $price + $extraPrice;
                    }
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
                                $price += $mini_plan->cod_charge;
                            }
                        }
                    }
                }
            } else {

                return $price = $this->InternationalShpping($order);
            }
        }
        return $price = [
            'tatal_cost' => $price,
            'extraweightPrice' => $extraPrice
        ];


    }

    public function getSystemFee($store, $request)
    {
        //     dd('sdf');
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        //   $date = new Carbon();
        // dd($date);
        $mini_plans = $this->checkStorePlans($request);

        if ($mini_plans['hasManyPlan']) {

            return $mini_plans['plans']->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->first()->system_fee;

        } else {

            return $mini_plans['plans']->where('from_date', '<=', Carbon::parse($request->from)->format('Y-m-d'))->first()->system_fee;

        }
    }

    public function exportSystemFee($request, $flag)
    {
        //  $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->firstOfMonth()->format('Y-m-d');
        $last = Carbon::create($request->to)->lastOfMonth()->format('Y-m-d');
        $data = [];
        $i = 0;
        //  dd($request->from);
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        //   $date = new Carbon();
        // dd($date);
        $year = $date->year;
        $month = substr($date->monthName, 0, 3);
        //dd($year,$month);
        $store = store::query()->with('hasPlan');
        if ($request->has('store') && $request->get('store') != '') {
            $store = $store->where('account_id', $request->get('store'));

        }
        $store = $store->get();
        foreach ($store as $str) {
            $system_fee_const = $this->getSystemFee($str, $request);
            if ($system_fee_const) {
                $data[$i]['store'] = $str->name;
                $data[$i]['statment_name'] = '5' . $month . $first . '_' . $month . $last . ' ' . $year;
                $data[$i]['date'] = Carbon::createFromDate($request->from)->endOfMonth();
                $data[$i]['total_item'] = 1;
                $data[$i]['weight'] = '0';
                $data[$i]['Total_Qty'] = 1;
                $data[$i]['Service_Type'] = 'Shipedge Service';
                $data[$i]['ID_Reg'] = 'Fee';
                $data[$i]['desc'] = 'Fee System: price for using the system';
                $data[$i]['country'] = 'SA';
                $data[$i]['city'] = 'Riyadh';
                $data[$i]['extraWeightPrice'] = '';
                $data[$i]['Cost_Value'] = $system_fee_const;
                $data[$i]['tracking_number'] = '';
                $i++;
            }

        }

        return $flag == true ? Excel::download(new HandlingPick($data), 'FeeSystem.xlsx') : $data;


    }

    public function getReturnCharge($order, $mini_plans, $hasManyPlan, $numberOfStoreOrders)
    {

        $order_type = $order->carrier == 'MORA' || $order->carrier == 'Jones' ? '_fr' : '';

        if ($hasManyPlan) {
            $mini_plan = $mini_plans->where('fromDate', '<=', $order->created_at)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>=', $numberOfStoreOrders)->first();

            if ($order->city == 'Riyadh') {
                //   dd( $mini_plan->{'return_charge_in'.$order_type});

                if ($order->cod_amount > 0 && $order->created_at >= '2021-10-01') {
                    $codBouns = $mini_plan->cod < 1 ? ($mini_plan->cod * $order->cod_amount) : $mini_plan->cod;

                    return $price = $mini_plan->{'return_charge_in' . $order_type} - $codBouns;
                } else {
                    return $price = $mini_plan->{'return_charge_in' . $order_type};
                }

                return $mini_plan->{'return_charge_in' . $order_type};
            } else {
                if ($order->cod_amount > 0 && $order->created_at >= '2021-10-01') {
                    $codBouns = $mini_plan->cod < 1 ? ($mini_plan->cod * $order->cod_amount) : $mini_plan->cod;
                    return $price = $mini_plan->{'return_charge_out' . $order_type} - $codBouns;
                } else {
                    $price = $mini_plan->{'return_charge_out' . $order_type};
                }

            }

            //$price = $plan->return_charge;
            return $price;
        } else {

            $mini_plan = $mini_plans->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();


            try {
                $cod_charge = (float)$mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type}; ///if cod < 0 cod chagre = cod *cod chagre

            } catch (\Exception $e) {
                dd($mini_plan, $order);
            }


            //    dd($mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type} );
            $cod_charge = (float)$mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type}; ///if cod < 0 cod chagre = cod *cod chagre
            //dd($mini_plan,$order);
            if ($order->city == 'Riyadh') {
                //&& ($order->store_id == 55 || $order->store_id == 56)
                if ($order->cod_amount > 0 && Carbon::parse($order->shipping_date)->format('Y-m-d') > '2021-09-30') {
                    // dd($mini_plan->{'return_charge_in'.$order_type} ,$mini_plan->{'cod_charge'.$order_type});
                    $price = $mini_plan->{'return_charge_in' . $order_type} - $cod_charge;
                } else {

                    $price = $mini_plan->{'return_charge_in' . $order_type};

                }

            } else {

                if ($order->cod_amount > 0 && Carbon::parse($order->shipping_date)->format('Y-m-d') > '2021-09-30') {
                    //  dd($mini_plan->{'return_charge_out'.$order_type});

                    $price = $mini_plan->{'return_charge_out' . $order_type} - $cod_charge;
                } else {

                    $price = $mini_plan->{'return_charge_out' . $order_type};

                }

            }

            if ($order->Qty_Item == 1) {
                return $price;
            } else {
                // dd($order->store->return_charge_each_extra);

                if (count(explode(',', $mini_plan->{'return_charge_each_extra' . $order_type})) == 1) {

                    $extraQty = $order->Qty_Item - 1;
                    //    dd($order->store->return_charge_each_extra);
                    $extraPrice = $mini_plan->{'return_charge_each_extra' . $order_type} * $extraQty;
//dd($extraPrice);

                    return $price + $extraPrice;
                } else {
                    $each_2nd = explode(',', $mini_plan->{'return_charge_each_extra' . $order_type});

                    if ($each_2nd[1] == 3 && $order->Qty_Item > 2) {
//dd('dfs');
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

    public function exportReturnCharge($request, $flag)
    {
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;
        //  dd('sdf');
        //     dd($order);
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $month = $date->month;

        $orders = order::query()->where([['carrier', '!=', 'Shipox'], ['order_status', 'Returned']])->whereNotNull('isnaad_return_date')->with(['store.hasPlan', 'store' => function ($q) use ($month) {
            $q->withCount(['orders as count_per_month' => function ($query) use ($month) {
                $query->whereMonth('created_at', $month);
            }]);
        }]);

        if ($request->has('store') && $request->get('store') != '') {
            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });

        }
        $orders->where('active', '=', '1');

        if ($request->has('from') && $request->get('from') != '') {
            $date = new Carbon($request->from);


            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $orders->whereBetween('isnaad_return_date', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');

                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween('isnaad_return_date', [$from, $to]);

            }
        }
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;

        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        $mini_plan = $this->checkStorePlans($request);


        $data = [];
        $i = 0;

        // dd($orders->get());
        $orders = $orders->get();
        $mini_plans = $this->checkStorePlans($request);

        if ($mini_plans['hasManyPlan']) {

            $mini_plan = $mini_plans['plans']->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->first();

        } else {
            $mini_plan = $mini_plans['plans'];

        }

        foreach ($orders as $or) {


            if (isset($or->store)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $or->shipping_date;
            $data[$i]['total_item'] = $this->totalItem($or->description);
            $data[$i]['total_Sku'] = $or->weight;
            $data[$i]['Total_Qty'] = $or->Qty_Item;
            // $data[$i]['Service_Type'] = $request->serviceType;
            $data[$i]['Service_Type'] = 'Return: Handling: Pick & Pack Services';
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'RETURN: Customer#:' . $or->fname . ' | ' . $or->city . ',' . $or->country . 'order#: ' . $or->order_number . ' > ' . $or->carrier;
            $data[$i]['country'] = $or->country == 'SA' ? $or->country : ($or->countries->is_gcc == 1 ? 'gcc' : $or->country);

            $data[$i]['city'] = $or->city == 'Riyadh' ? 'in Riyadh ' : 'out Riyadh';
            $data[$i]['extraWeightPrice'] = '';

            $data[$i]['Cost_Value'] = $this->getReturnCharge($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
            $data[$i]['tracking_number'] = '';
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'ReturnInvoice.xlsx') : $data;
    }

    public function exportReplanchment($request, $flag)
    {
        $rep = replenishment::query();
        if ($request->has('store') && $request->get('store') != '') {
            $rep = $rep->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
            if ($request->has('from') && $request->get('from') != '') {
                $date = new Carbon($request->from);
                if ($request->has('to') && $request->get('to') != '') {
                    $to = new \DateTime($request->get('to'));
                    $to = $to->format('y/m/d');
                    $from = new \DateTime($request->get('from'));
                    $from = $from->format('y/m/d');
                    $orders = $rep->whereBetween('date', [$from, $to]);
                } else {
                    $from = new \DateTime($request->get('from'));
                    $from = $from->format('y/m/d');
                    $to = Carbon::now();
                    $to->format('y/m/d');
                    $to = $to->toDateString();
                    $orders = $rep->whereBetween('date', [$from, $to]);
                }
            }
        }
        $data = [];
        $i = 0;
        $reps = $rep->get();
        //  dd($request->from);
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;

        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);


        foreach ($reps as $rep) {
            $replenishment_const = $this->getReplanchmentCost($rep, $request);
            if ($replenishment_const) {
                if (isset($rep->store->name)) {
                    $data[$i]['store'] = $rep->store->name;
                } else {
                    $data[$i]['store'] = '';
                }
                $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
                $data[$i]['date'] = $rep->date;
                $data[$i]['total_item'] = 1;
                $data[$i]['weight'] = '';
                $data[$i]['Total_Qty'] = $rep->quantity_recived;
                // $data[$i]['Service_Type'] = $request->serviceType;
                $data[$i]['Service_Type'] = 'Replenishment : Service & Barcoding';
                $data[$i]['ID_Reg'] = $rep->rep_id;
                $data[$i]['desc'] = $rep->quantity_recived . ' Units received  in Replenishment# ' . $rep->rep_id;
                $data[$i]['country'] = 'SA';
                $data[$i]['city'] = 'in Riyadh ';
                $data[$i]['extraWeightPrice'] = '';
                $data[$i]['Cost_Value'] = $replenishment_const;
                $data[$i]['tracking_number'] = '';
                $i++;
            }
        }


        return $flag == true ? Excel::download(new HandlingPick($data), 'replanchment.xlsx') : $data;


    }

    public function getReplanchmentCost($rep, $request)
    {
        $mini_plans = $this->checkStorePlans($request);
        if ($mini_plans['hasManyPlan']) {

            $mini_plan = $mini_plans['plans']->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->first();

        } else {
            $mini_plan = $mini_plans['plans'];

        }

        $fromDate = Carbon::createFromDate($request->from)->month;
        $plans_rep = 0;

        $countOrders = $rep->store->count_per_month;
//$mini_plan = $mini_plans->where('from_date', '<=',Carbon::parse($order->shipping_date)->format('Y-m-d') )->first();


        if ($mini_plans['hasManyPlan']) {

            return $mini_plans['plans']->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->first()->Reciving_replanchment * $rep->quantity_recived;

        } else {

            return $mini_plans['plans']->where('from_date', '<=', Carbon::parse($request->from)->format('Y-m-d'))->first()->Reciving_replanchment * $rep->quantity_recived;

        }


    }

    public function getIsnaadReturnCost($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {

        $order_type = $order->carrier == 'MORA' || $order->carrier == 'Jones' ? '_fr' : '';

        if ($hasManyPlan) {
            $mini_plan = $mini_plan->where('fromDate', '<', $order->created_at)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

        } else {
            $mini_plan = $mini_plan->where('from_date', '<=', $order->created_at->format('Y-m-d'))->first();
        }

        if ($order->order->country == 'SA') {
            $alloWeightInSA = $hasManyPlan ? $mini_plan->{'allow_wight_sa' . $order_type} : $mini_plan->{'allowed_weight_in_sa' . $order_type};
            if ($order->order->city == 'Riyadh') {

                $orderWeight = $order->order->weight;
                $price = $mini_plan->{'in_side_ryad' . $order_type};

                if ($orderWeight > $alloWeightInSA) {

                    $extraweight = $orderWeight - $alloWeightInSA;
                    $extraPrice = ($extraweight * $mini_plan->{'add_cost_in_sa' . $order_type});

                    $price = $price + $extraPrice;
                }

                return $price;
            } else {

                $orderWeight = $order->order->weight;
                $price = $mini_plan->{'out_side_ryad' . $order_type};

                if ($orderWeight > $mini_plan->{'allowed_weight_in_sa' . $order_type}) {
                    $extraweight = $orderWeight - $mini_plan->{'allow_wight_sa' . $order_type};
                    $extraPrice = ($extraweight * $mini_plan->{'add_cost_in_sa' . $order_type});
                    $price = $price + $extraPrice;
                }

                return $price;
            }


        } else {

            return $this->InternationalShpping($order, false);
        }
    }

    public function exportIsnaadReturn($request, $flag)
    {
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;
        $order = isnaad_return::query()->with('order', 'store', 'carrier');
        if ($request->has('from') && $request->get('from') != '') {
            $date = new Carbon($request->from);


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
        if ($request->has('store') && $request->get('store') != '') {
            //  dd('sfd');
            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });

        }

        $order = $order->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);
        $mini_plans = $this->checkStorePlans($request);
        if ($mini_plans['hasManyPlan']) {

            $mini_plan = $mini_plans['plans']->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->first();

        } else {
            $mini_plan = $mini_plans['plans'];

        }

        foreach ($order as $or) {

            if (isset($or->store)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $or->order->shipping_date;
            $data[$i]['total_item'] = 0;
            $data[$i]['weight'] = $or->order->weight;
            $data[$i]['Total_Qty'] = $or->order->Qty_Item;
            $data[$i]['Service_Type'] = 'Shipping: Client Return - Carrier & Transportation';
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'SHiPPING: Order#:' . $or->order_number . '-Carrier>' . $or->carrier->name . ':' . $or->traking_number;
            if ($or->order->country == 'SA') {
                $data[$i]['country'] = 'SA';
            } else {
                $data[$i]['country'] = $or->order->countries->is_gcc ? 'GCC' : $or->country;
            }

            $data[$i]['city'] = $or->order->city;
            $data[$i]['extraWeightPrice'] = '';
            $data[$i]['Cost_Value'] = $this->getIsnaadReturnCost($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
            $data[$i]['tracking_number'] = '';
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    public function InternationalShpping($order, $flag = true)
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


        // dd($order->countries->is_gcc);
        if ($order->countries->is_gcc) {

            $price = $order->shipping_date > '2021-09-30' ? 40 : 35;
            $bounus = $order->shipping_date > '2021-09-30' ? 10 : 8;

            if ($orderWeight > $order->store->allow_wight_gcc) {
                $extraweight = $orderWeight - $order->store->allow_wight_gcc;

                // dd($order->weight );
                //  dd($extraweight,$order->weight,$order->store->allow_wight_gcc);
                $extraPrice = ($extraweight / .5) * $bounus;
//dd($extraPrice,$order,$extraweight,$orderWeight);

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

            $last_price = $order->countries->first_half;

            if ($orderWeight < .5) {

                return [
                    'tatal_cost' => round($last_price + ($last_price * $dis), 2),
                    'extraweightPrice' => 0
                ];


            } elseif ($orderWeight > .5 && $orderWeight <= 10) {


                $extra_weight = $orderWeight - .5;
                $extra_weight_in_half = $extra_weight / .5;
                $extra_weight_in_half_price = $extra_weight_in_half * $order->countries->each_aditional_afte_half;
                $extraPrice = $extra_weight_in_half_price;
                $last_price = $last_price + $extra_weight_in_half_price;
                //  dd($last_price);

            } elseif ($orderWeight > 10 && $orderWeight <= 15) {
                $extra_weight = $orderWeight - .5;
                $extra_weight_in_half = $extra_weight / .5;
                $extra_weight_in_half_price = $extra_weight_in_half * $order->countries->each_aditional_afte_half;
                $extra_weight = $orderWeight - 10;
                $extra_weight_in_half = $extra_weight / .5;
                $extra_weight_in_ten = $extra_weight_in_half * $order->countries->each_aditional_afte_each_aditional_after_ten;
                $extraPrice = $extra_weight_in_half_price + $extra_weight_in_ten;
                $last_price = $last_price + $extra_weight_in_half_price + $extra_weight_in_ten;
            } elseif ($orderWeight > 15) {
                $extra_weight = $orderWeight - .5;
                $extra_weight_in_half = $extra_weight / .5;
                $extra_weight_in_half_price = $extra_weight_in_half * $order->countries->each_aditional_afte_half;
                $extra_weight = $orderWeight - 10;
                $extra_weight_in_half = $extra_weight / .5;
                $extra_weight_in_ten = $extra_weight_in_half * $order->countries->each_aditional_afte_each_aditional_after_ten;
                $extra_weight = $orderWeight - 15;
                $extra_weight_in_half = $extra_weight / .5;
                $extra_weight_in_fifty = $extra_weight_in_half * $order->countries->each_aditional_afte_each_aditional_after_each_aditional_after_fiften;
                $extraPrice = $extra_weight_in_half_price + $extra_weight_in_ten + $extra_weight_in_fifty;
                $last_price = $last_price + $extra_weight_in_half_price + $extra_weight_in_ten + $extra_weight_in_fifty;

            }
            if ($flag) {

                return [
                    'tatal_cost' => $last_price + ($dis * $last_price),
                    'extraweightPrice' => $extraPrice
                ];
            }

            return round($last_price + ($last_price * $dis), 2);
        }

    }

    public function exportInternationalReturn($request, $flag)
    {

        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;
        $orders = international_return::query()->with(['order.countries', 'carrier', 'store'])->where('active', 1);

        if ($request->has('from') && $request->get('from') != '') {
            $date = new Carbon($request->from);


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

        if ($request->has('store') && $request->get('store') != '') {
            //  dd('sfd');
            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });

        }

        $order = $orders->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);


        foreach ($order as $or) {

            if (isset($or->store)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $or->order->shipping_date;
            $data[$i]['total_item'] = 0;
            $data[$i]['weight'] = $or->order->chargalbeWeight;
            $data[$i]['Total_Qty'] = $or->order->Qty_Item;
            $data[$i]['Service_Type'] = 'international  Return - Carrier & Transportation';
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'SHiPPING: Order#:' . $or->order_number . '-Carrier>' . $or->carrier->name . ':' . $or->traking_number;
            if ($or->order->country == 'SA') {
                $data[$i]['country'] = 'SA';
            } else {
                $data[$i]['country'] = $or->order->countries->is_gcc ? 'GCC' : $or->country;
            }

            $data[$i]['city'] = $or->order->city;
            $data[$i]['extra_cost'] = '';
            $data[$i]['Cost_Value'] = $this->international_return($or);

            $data[$i]['tracking_number'] = '';
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    private function filter($query, $request)
    {

        if ($request->has('from') && $request->get('from') != '') {
            $date = new Carbon($request->from);


            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $query = $query->whereBetween('shipping_date', [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');

                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $query = $query->whereBetween('shipping_date', [$from, $to]);

            }
        }
        // dd($orders->toSql());
        if ($request->has('store') && $request->get('store') != '') {

            $query = $query->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });

        }
        return $query;
    }

    public function checkStorePlans($request)
    {
        $numberOfStoreOrders = 0;
        $hasManyPlan = store::where('account_id', '=', $request->get('store'))->first()->hasMultiplePlan;

        if ($hasManyPlan) {

            $mini_plan = nstoreplan::where('store_id', $request->get('store'))->orderBy('fromDate', 'desc')->get();
            $numberOfStoreOrders = order::where('store_id', $request->get('store'))->whereBetween('created_at', [$request->get('from'), $request->get('to')])->count();
            $hasManyPlan = true;

//            $numberOfStoreOrders =order::select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
//                ->where('store_id', $request->get('store'))
//                ->whereBetween('created_at', [$request->get('from'), $request->get('to')])
//                ->groupby('year','month')
//                ->get();

        } else {

            $mini_plan = masterPlan::where('store_id', $request->get('store'))->orderBy('from_date', 'desc')->get();

        }


        return [
            'hasManyPlan' => $hasManyPlan,
            'plans' => $mini_plan,
            'numberOfStoreOrders' => $numberOfStoreOrders
        ];
    }

    public function international_return($order)
    {

        $price = $order->order->countries->return_charge_first_half;

        $weight = $order->order->chargalbeWeight - .5;
        if ($weight > 0) {
            $halfs = (ceil($weight * 2) / 2) / .5;

            $price = ($price + $halfs * $order->order->countries->return_charge_after_first_half);


        }
        $price = ($price + 250);
        $price = $price + $price * .15;
        return $price;
    }

    public function StorageExport($request, $flag = true)
    {

        $to = Carbon::parse($request->to)->addDay();
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;
        $stprages = storage::where('store_id', $request->store)->with('store:id,account_id,name')->whereBetween('date', [$request->from, $to]);


        $stprages = $stprages->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        foreach ($stprages as $stoage) {

            $data[$i]['store'] = $stoage->store->name ?? '';
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $stoage->date;
            $data[$i]['sum_of_sin_volume'] = $stoage->sum_of_sin_volume;
            $data[$i]['weight'] = 0;
            $data[$i]['Total_Qty'] = $stoage->sum_of_product_qty;
            $data[$i]['Service_Type'] = 'Storage: Shelving and warehousing';
            $data[$i]['ID_Reg'] = $stoage->storage_type;
            $data[$i]['desc'] = $stoage->description;
            $data[$i]['country'] = 'SA';
            $data[$i]['city'] = 'in Riyadh ';
            $data[$i]['extra'] = '';
            $data[$i]['Cost_Value'] = $stoage->cost;

            $data[$i]['tracking_number'] = '';
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    public function TransportaionExport($request, $flag = true)
    {

        $to = Carbon::parse($request->to)->addDay();
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;


        $transportations = transportation_cost::where('store_id', $request->store)->whereBetween('date', [$request->from, $to]);


        $transportations = $transportations->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        foreach ($transportations as $_transportations) {
            if ($_transportations->cost) {
                $data[$i]['store'] = $_transportations->store->name ?? '';
                $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
                $data[$i]['date'] = $_transportations->date;
                $data[$i]['sum_of_sin_volume'] = '';
                $data[$i]['weight'] = 0;
                $data[$i]['Total_Qty'] = $_transportations->total_quantity;
                $data[$i]['Service_Type'] = 'Isnaad Transportaion';
                $data[$i]['ID_Reg'] = '';
                $data[$i]['desc'] = $_transportations->description;
                $data[$i]['country'] = 'SA';
                $data[$i]['city'] = 'in Riyadh ';
                $data[$i]['extra'] = '';
                $data[$i]['Cost_Value'] = $_transportations->cost;
                $data[$i]['tracking_number'] = '';
                $i++;
            }

        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    public function DiscountExport($request, $flag = true)
    {

        $to = Carbon::parse($request->to)->addDay();
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;


        $discounts = invoice_discount::where('store_id', $request->store)->whereBetween('date', [$request->from, $to]);


        $discounts = $discounts->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        foreach ($discounts as $discount) {

            $data[$i]['store'] = $discount->store->name ?? '';
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $discount->date;
            $data[$i]['sum_of_sin_volume'] = '';
            $data[$i]['weight'] = 0;
            $data[$i]['Total_Qty'] = $discount->total_item;
            $data[$i]['Service_Type'] = $discount->service_type;
            $data[$i]['ID_Reg'] = '';
            $data[$i]['desc'] = $discount->description;
            $data[$i]['country'] = 'SA';
            $data[$i]['city'] = 'in Riyadh ';
            $data[$i]['extra'] = '';
            $data[$i]['Cost_Value'] = $discount->total_disccount;
            $data[$i]['tracking_number'] = '';
            $i++;


        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    public function ExtraCostExport($request, $flag = true)
    {

        $to = Carbon::parse($request->to)->addDay();
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;

        $ExtraCosts = invoice_extra_cost::where('store_id', $request->store)->whereBetween('date', [$request->from, $to]);

        $ExtraCosts = $ExtraCosts->get();

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        foreach ($ExtraCosts as $_ExtraCosts) {

            $data[$i]['store'] = $_ExtraCosts->store->name ?? '';
            $data[$i]['statment_name'] = '5_' . $first . $month_from . $year_from . '_TO_' . $last . $month_to . $year_to;
            $data[$i]['date'] = $_ExtraCosts->date;
            $data[$i]['sum_of_sin_volume'] = '';
            $data[$i]['weight'] = 0;
            $data[$i]['Total_Qty'] = $_ExtraCosts->total_item;
            $data[$i]['Service_Type'] = 'another expensive';
            $data[$i]['ID_Reg'] = '-';
            $data[$i]['desc'] = $_ExtraCosts->description;
            $data[$i]['country'] = 'SA';
            $data[$i]['city'] = 'in Riyadh ';
            $data[$i]['extra'] = '';
            $data[$i]['Cost_Value'] = $_ExtraCosts->cost;
            $data[$i]['tracking_number'] = '';
            $i++;


        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    public function get_total_new_invoice_report(Request $request)
    {
        $orders = $this->InvoiceReportDate($request, true);
        // $orders
    }

}
