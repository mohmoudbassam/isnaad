<?php

namespace App\Http\Controllers\Reports;

use App\Models\box;
use App\Models\confirm_invoice;
use App\Models\international_return;
use App\Models\invoice_discount;
use App\Models\invoice_extra_cost;
use App\Models\order_box;
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
use App\Models\invoice_excel;
use Illuminate\Support\Facades\Storage as laravelStorage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\invoicies;

class InvoiceReportMaster extends Controller
{
    public static $store = null;
    public static $count = null;
    public static $store_ids = null;

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

        $orders = $orders->select(
            'id','order_status', 'chargalbeWeight', 'shipping_number', 'carrier_charge', 'carrier', 'weight', 'tracking_number', 'cod_amount', 'store_id', 'Qty_Item', 'country', 'city', 'shipping_date', 'order_number', 'created_at')

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

                    return $this->getReturnCharge($orders, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']) ? $this->getReturnCharge($orders, $mini_plan, $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']) : '0';
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
            if($or->store_id==74){
                $weight= $or->Qty_Item * 5.75 ;
            }else{
                if($or->country == 'SA'){
                    $weight= ceil($or->weight);
                }else{
                    $weight=   $or->chargalbeWeight;
                }

            }
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
            $data[$i]['weight'] =$weight;
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
            $data[$i]['Cost_Value'] = number_format($total_cost,2,'.', '');
            $data[$i]['tracking_number'] = $or->tracking_number;
            $data[$i]['cod_amount'] = $or->cod_amount;
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

        $data = [];
        $i = 0;
        $date_from = new Carbon($request->from);
        $date_to = new Carbon($request->to);
        $year_from = $date_from->year;
        $year_to = $date_to->year;
        $month_from = substr($date->monthName, 0, 3);
        $month_to = substr(Carbon::create($request->to)->monthName, 0, 3);

        foreach ($order as $or) {
            $handling=     $this->getCostForHandilingAndPick($or, $request, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
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
            try {
                $data[$i]['country'] = $or->country == 'SA' ? $or->country : ($or->countries->is_gcc == 1 ? 'gcc' : $or->country) ?? '-';
            } catch (\Exception $e) {
                dd($or);
            }

            $data[$i]['city'] = $or->city == 'Riyadh' ? 'in Riyadh ' : 'out Riyadh';
            $data[$i]['extraWeightPrice'] = 0;

            $data[$i]['Cost_Value'] =number_format($handling,2,'.', '')?:'0';

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


        $order_type = $order->carrier == 'MORA'? '_fr' : '';
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
        $box_isnaad_count=  $order->box_isnaad_count ==0 ? 1:$order->box_isnaad_count;
        $client_box=  $mini_plan->client_packaging ==0 ? 1:$mini_plan->client_packaging;
        $price =($mini_plan->isnaad_packaging * $box_isnaad_count)+($mini_plan->client_packaging * $client_box);

        if ($order->Qty_Item == 1) {
            return $price +$mini_plan->{'processing_charge' . $order_type};
        } else {

            if (count(explode(',', $mini_plan->{'each_2nd_units' . $order_type})) == 1) {///when  each_2nd_units not has comma
                $extraQty = $order->{'Qty_Item' . $order_type} - 1;
                $extraPrice = $mini_plan->{'each_2nd_units' . $order_type}  * $extraQty;

                return $price+ $mini_plan->{'processing_charge' . $order_type} +$extraPrice;
            } else {

                $each_2nd = explode(',', $mini_plan->{'each_2nd_units' . $order_type});

                if ($each_2nd[1] == 3 && $order->Qty_Item > 2) {

                    $extraQty = $order->Qty_Item - 2;
                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price + $extraPrice+$mini_plan->{'processing_charge' . $order_type};
                } elseif ($each_2nd[1] == 5 && $order->Qty_Item > 4) {

                    $extraQty = $order->Qty_Item - 4;
                    $extraPrice = (float)$each_2nd[0] * $extraQty;

                    return $price + $extraPrice+$mini_plan->{'processing_charge' . $order_type};
                } elseif ($each_2nd[1] == 4 && $order->Qty_Item > 3) {

                    $extraQty = $order->Qty_Item - 3;
                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price  + $extraPrice+$mini_plan->{'processing_charge' . $order_type};
                } elseif ($each_2nd[1] == 1 && $order->Qty_Item > 1) {

                    $extraQty = $order->Qty_Item - 1;

                    $extraPrice = (float)$each_2nd[0] * $extraQty;
                    return $price  + $extraPrice+$mini_plan->{'processing_charge' . $order_type};
                } else {

                    return $mini_plan->{'processing_charge' . $order_type}+$price ;
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

    public function ExportAll($request, $Confermed = false,$updateConfermed = false)
    {

        $data = [];
        if ($request->system) {
            $systemFee = $this->exportSystemFee($request, false);
        }else{
            $systemFee=[];
        }
        $replenishment = $this->exportReplanchment($request, false);

        $storage = $this->StorageExport($request, false);

        $Handling = $this->HandlingPickExport($request, false);

        $Shipping = $this->orderExportExcel($request, false);
        $Return = $this->exportReturnCharge($request, false);
        $IsnaadReturn = $this->exportIsnaadReturn($request, false);
        $InternationalReturn = $this->exportInternationalReturn($request, false);
        $Transportaion = $this->TransportaionExport($request, false);
        $ExtraCost = $this->ExtraCostExport($request, false);
        $Discount = $this->DiscountExport($request, false);

        $data = array_merge(
            $systemFee,
            $replenishment,
            $storage,
            $Handling,
            $Shipping,
            $Return,
            $IsnaadReturn,
            $InternationalReturn,
            $Transportaion,
            $ExtraCost,
            $Discount
        );
        $to_date=Carbon::parse($request->to)->addDay()->format('Y-m-d');

        $sum = 0;

        foreach ($data as $_data) {
            try {
                $sum += $_data['Cost_Value'];
            } catch (\Exception $e) {
                dd($_data);
            }

        }
        $index = count($data) + 1;

        $data[$index]['store'] = 'TOTAL';
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
        $Confermed ?$data[$index]['extraWeightPrice'] = 0 :''  ;
        $data[$index]['Cost_Value'] = $sum;
        $Confermed ?$data[$index]['tracking_number'] = 0 :''  ;
        $store = store::query()->where('account_id', $request->store)->first();

        if($updateConfermed){
            $invoice=$request->invoice;
            $pdfResult = $this->pdfInstall(
                $request,
                $systemFee,
                $replenishment,
                $storage,
                $Handling,
                $Shipping,
                $Return,
                $IsnaadReturn,
                $InternationalReturn,
                $Transportaion,
                $ExtraCost,
                $Discount,
                $invoice->inv_number,
                true
            );

            $update= isnaad_return::where('account_id',$request->store)
                ->whereBetween('created_at',[$request->from,$to_date])->update([
                    'inv_num'=>$invoice->id
                ]);
            $invoice->pdf = $pdfResult['file_name'];
            $Excel = '/confirmed/' . $invoice->inv_number. '.xlsx';
        }
        elseif ($Confermed) {

            $inv_number = $this->getNewConfirmInvoiceNumber();
            $Excel = '/confirmed/' . $inv_number . '.xlsx';
            $invoice=  confirm_invoice::create([
                'draf_id' => $request->draft_id,
                'excel'=>$Excel,
                'inv_number'=>$inv_number

            ]);
            $pdfResult = $this->pdfInstall(
                $request,
                $systemFee,
                $replenishment,
                $storage,
                $Handling,
                $Shipping,
                $Return,
                $IsnaadReturn,
                $InternationalReturn,
                $Transportaion,
                $ExtraCost,
                $Discount,
                $inv_number,
                true
            );
            $invoice->pdf = $pdfResult['file_name'];


            order::where('store_id',$request->store)->whereBetween('shipping_date',[$request->from,$request->to])->update([
                'inv_num'=>$invoice->id
            ]);

            $update= isnaad_return::where('account_id',$request->store)
                ->whereBetween('created_at',[$request->from,$to_date])->update([
                    'inv_num'=>$invoice->id
                ]);

        }
        else {

            $inv_number = $this->getNewInvoiceNumber();
            $Excel = '/draf/' . $inv_number . '.xlsx';
            $invoice = invoicies::create([
                'inv_number' => $inv_number,
                'from_date' => $request->from,
                'to_date' => $request->to,
                'store_id' => $store->account_id,
                'confirmed' => 0,
                'excel' => $Excel,
                'system'=>$request->system

            ]);
            $pdfResult = $this->pdfInstall(
                $request,
                $systemFee,
                $replenishment,
                $storage,
                $Handling,
                $Shipping,
                $Return,
                $IsnaadReturn,
                $InternationalReturn,
                $Transportaion,
                $ExtraCost,
                $Discount,
                $invoice->inv_number
            );
            $invoice->pdf = $pdfResult['file_name'];
            // dd($pdfResult);



        }
        $invoice->total_before_vat = $pdfResult['total_before_vat'];
        $invoice->total_after_vat = $pdfResult['total_after_vat'];
        $invoice->total_vat = $pdfResult['total_vat'];
        $invoice->save();
        if($Confermed||$updateConfermed){
            $Confermed=true;
        }
        Excel::store(new HandlingPick($data,$Confermed), $Excel, 'uploads');
        return redirect()->back()->with(['successExport'=> 'invoice report exported successfully','from_date'=>$request->from,'to_date'=>$request->to]);
    }

    public function hasPlan($orders, $request, $mini_plan, $hasManyPlan, $numberOfStoreOrders)
    {

        $order_type = $orders->carrier == 'MORA' ? '_fr' : '';

        if ($hasManyPlan) {

            $mini_plan = $mini_plan->where('fromDate', '<=', $orders->shipping_date)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

            $price = $mini_plan->{'processing_charge' . $order_type};

            $anotherQty = explode(',', $mini_plan->{'each_2nd_units' . $order_type});
            $price =($mini_plan->isnaad_packaging * $orders->box_isnaad_count)+($mini_plan->client_packaging *$orders->box_client_count);
            if ($orders->Qty_Item == 1) {

                return $price + $mini_plan->{'isnaad_packaging' . $order_type}+ $mini_plan->{'processing_charge' . $order_type};
            } else {

                if (count(explode(',', $mini_plan->{'each_2nd_units' . $order_type})) == 1) {///when  each_2nd_units not has comma

                    $extraQty = $orders->Qty_Item - 1;

                    $extraPrice = $mini_plan->{'each_2nd_units' . $order_type} * $extraQty;

                    return $price + $mini_plan->{'isnaad_packaging' . $order_type}+$mini_plan->{'processing_charge' . $order_type} + $extraPrice;

                } else {
                    $each_2nd = explode(',', $mini_plan->{'each_2nd_units' . $order_type});

                    if ($each_2nd[1] == 3) {

                        $extraQty = $orders->Qty_Item - 2;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;

                        return $price + $mini_plan->{'isnaad_packaging' . $order_type} + $extraPrice+$mini_plan->{'processing_charge' . $order_type};
                    } elseif ($each_2nd[1] == 5) {
                        $extraQty = $orders->Qty_Item - 4;
                        $extraPrice = (float)$each_2nd[0] * $extraQty;
                        return $price + $extraPrice+$mini_plan->{'processing_charge' . $order_type};
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
        $order_type = $order->carrier == 'MORA'  ? '_fr' : '';

        if ($order->order_status == 'cancelled') {
            $mini_plan= $this->get_order_plan($order,$mini_plan,$numberOfStoreOrders,$hasManyPlan);
            return [
                'tatal_cost' => Carbon::parse($order->shipping_date)->format('Y-m-d') > '1-1-2022' ? $mini_plan->return_charge_out : $this->getReturnCharge($order, $mini_plan, $hasManyPlan, $numberOfStoreOrders),
                'extraweightPrice' => 0
            ];
        }
        if ($order->carrier == 'Pick' && $order->store_id != 74) {

            return [
                'tatal_cost' => 5,
                'extraweightPrice' => 0
            ];
        }

        if ($order->created_at->format('Y-m') >= '2021-06') {
            $orderWeight = ceil($order->weight);
            if($order->store_id==74){
                $orderWeight =  $order->Qty_Item * 5.75 ;

            }

        } else {

            $orderWeight = $order->weight;
        }

        if ($hasManyPlan) {

            $mini_plan= $this->get_order_plan($order,$mini_plan,$numberOfStoreOrders,$hasManyPlan);

            $allow_wight_sa = $mini_plan->{'allow_wight_sa' . $order_type};
            $shipping_charge_in_ra = $mini_plan->{'in_side_ryad' . $order_type};
            $shipping_charge_out_ra = $mini_plan->{'out_side_ryad' . $order_type};
            // dd($shipping_charge_in_ra,$mini_plan);

            $add_cost_in_sa = $mini_plan->{'add_cost_in_sa' . $order_type};
            $cod_charge = $mini_plan->{'cod' . $order_type};

            if ($order->country == 'SA') {

                if ($order->city == 'Riyadh') {
                    $price = $shipping_charge_in_ra;
                    if ( $orderWeight  > $allow_wight_sa) {
                        $price += $add_cost_in_sa * ceil(( $orderWeight  - $allow_wight_sa));

                    }


                } else {
                    $price = $shipping_charge_out_ra;

                    if ( $orderWeight  > $allow_wight_sa) {
                        $price += $add_cost_in_sa * ceil(( $orderWeight  - $allow_wight_sa));
                    }


                }
                if ($order->cod_amount > 0) {
                    $cod_charge=count($mini_plan->cod_plan) ? $this->get_cod_plan($order,$mini_plan):$cod_charge;

                    if ($cod_charge < 1) {

                        $price = $price + ($order->cod_amount * $cod_charge);
                    } else {

                        $price += $cod_charge;
                    }
                }


                if ($orderWeight > $allow_wight_sa) {
                    $extraweight = ceil( $orderWeight ) - $allow_wight_sa;
                    $extraPrice = ($extraweight * $mini_plan->extra_wight_ksa);
                    $price = $price + $extraPrice;
                }

            } else {
                //  dd($mini_plan);
                //   $mini_plan= $this->get_order_plan($order,$mini_plan,$numberOfStoreOrders,$hasManyPlan);

                return $price = $this->InternationalShpping($order,$mini_plan);
            }
        } else {
            $mini_plan= $this->get_order_plan($order,$mini_plan,$numberOfStoreOrders,$hasManyPlan);

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
                // dd($mini_plan);
                // $mini_plan= $this->get_order_plan($order,$mini_plan,$numberOfStoreOrders,$hasManyPlan);

                return $price = $this->InternationalShpping($order,$mini_plan);
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

        $mini_plans = $this->checkStorePlans($request);

        if ($mini_plans['hasManyPlan']) {

            $plan = $mini_plans['plans']
                ->where('fromDate', '<=', $request->from)
                ->where('fromDate', '<', $request->to)
                ->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])
                ->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->filter(function ($item) use ($request) {
                    return (data_get($item, 'fromDate')) < $request->to && (data_get($item, 'fromDate')) < $request->to;
                })->first();


            return $plan->system_fee;

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
                $data[$i]['statment_name'] ='5_' . $first . $month . $year . '_TO_' . $last . $month . $year;
                $data[$i]['date'] = Carbon::createFromDate($request->from)->endOfMonth()->format('Y-m-d');
                $data[$i]['total_item'] = 1;
                $data[$i]['weight'] = '0';
                $data[$i]['Total_Qty'] = 1;
                $data[$i]['Service_Type'] = 'Fee System price';
                $data[$i]['ID_Reg'] = '-';
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

        $order_type = $order->carrier == 'MORA'  ? '_fr' : '';

        if ($hasManyPlan) {
            $mini_plan = $mini_plans->where('fromDate', '<=', $order->shipping_date)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>=', $numberOfStoreOrders)->first();

            if ($order->city == 'Riyadh') {

                if ($order->cod_amount > 0 && $order->shipping_date >= '2021-10-01') {
                    $cod_charge=count($mini_plan->cod_plan) ? $this->get_cod_plan($order,$mini_plan):$mini_plan->cod;
                    $codBouns = $cod_charge < 1 ? ($cod_charge* $order->cod_amount) :$cod_charge;
                    return $price = $mini_plan->{'return_charge_in' . $order_type} - $codBouns;
                } else {
                    return $price = $mini_plan->{'return_charge_in' . $order_type};
                }


            } else {
                if ($order->cod_amount > 0 && $order->shipping_date >= '2021-10-01')
                {
                    $cod_charge=count($mini_plan->cod_plan) ? $this->get_cod_plan($order,$mini_plan):$mini_plan->cod;
                    $codBouns = $cod_charge < 1 ? ($cod_charge* $order->cod_amount) :$cod_charge;
                    return $price = $mini_plan->{'return_charge_out' . $order_type} - $codBouns;
                } else {
                    $price = $mini_plan->{'return_charge_out' . $order_type};
                }

            }


            return $price;
        } else {
            $mini_plan = $mini_plans->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d H:i:s'))->first();


            try {
                $cod_charge = (float)$mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type}; ///if cod < 0 cod chagre = cod *cod chagre

            } catch (\Exception $e) {
                dd($mini_plan, $order, Carbon::parse($order->shipping_date)->format('Y-m-d'));
            }



            $cod_charge = (float)$mini_plan->{'cod_charge' . $order_type} < 1 ? $mini_plan->{'cod_charge' . $order_type} * $order->cod_amount : $mini_plan->{'cod_charge' . $order_type}; ///if cod < 0 cod chagre = cod *cod chagre

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
            $data[$i]['weight'] = $or->weight;
            $data[$i]['Total_Qty'] = $or->Qty_Item;
            // $data[$i]['Service_Type'] = $request->serviceType;
            $data[$i]['Service_Type'] = 'Return: Handling: Pick & Pack Services';
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'RETURN: Customer#:' . $or->tracking_number . ' | ' . $or->city . ',' . $or->country . 'order#: ' . $or->order_number . ' > ' . $or->carrier;
            $data[$i]['country'] = $or->country == 'SA' ? $or->country : ($or->countries->is_gcc == 1 ? 'gcc' : $or->country);

            $data[$i]['city'] = $or->city == 'Riyadh' ? 'in Riyadh ' : 'out Riyadh';
            $data[$i]['extraWeightPrice'] = '';

            $data[$i]['Cost_Value'] = number_format($this->getReturnCharge($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']),2,'.', '');
            $data[$i]['tracking_number'] = '';
            $data[$i]['cod_amount'] = $or->cod_amount;
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
                $data[$i]['weight'] = '0';
                $data[$i]['Total_Qty'] = $rep->quantity_recived;
                // $data[$i]['Service_Type'] = $request->serviceType;
                $data[$i]['Service_Type'] = 'Replenishment : Service & Barcoding';
                $data[$i]['ID_Reg'] = $rep->rep_id;
                $data[$i]['desc'] = $rep->quantity_recived . ' Units received  in Replenishment# ' . $rep->rep_id;
                $data[$i]['country'] = 'SA';
                $data[$i]['city'] = 'in Riyadh ';
                $data[$i]['extraWeightPrice'] = '';
                $data[$i]['Cost_Value'] = number_format($replenishment_const,2,'.', '');
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

            $mini_plan = $mini_plans['plans']
                ->where('from_num', '<=', $mini_plans['numberOfStoreOrders'])
                ->where('to_num', '>=', $mini_plans['numberOfStoreOrders'])->filter(function ($item) use ($rep) {
                    return (data_get($item, 'fromDate')) < $rep->date && (data_get($item, 'fromDate')) < $rep;
                })->first();

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

    public function getIsnaadReturnCost($order, $mini_plans, $hasManyPlan, $numberOfStoreOrders)
    {

        $order_type = $order->carrier == 'MORA' ? '_fr' : '';

        if ($hasManyPlan) {
            $mini_plan = $mini_plans->where('fromDate', '<', $order->created_at)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

        } else {
            $mini_plan = $mini_plans->where('from_date', '<=', $order->created_at->format('Y-m-d'))->first();
        }

        $alloWeightInSA = $hasManyPlan ? $mini_plan->{'allow_wight_sa' . $order_type} : $mini_plan->{'allowed_weight_in_sa' . $order_type};
        if ($order->order->country == 'SA') {

            if ($order->order->city == 'Riyadh') {

                $orderWeight =ceil($order->order->weight) ;
                $price = $mini_plan->{'in_side_ryad' . $order_type};

                if ($orderWeight > $alloWeightInSA) {

                    $extraweight = $orderWeight - $alloWeightInSA;
                    $extraPrice = ($extraweight * $mini_plan->{'add_cost_in_sa' . $order_type});

                    $price = $price + $extraPrice;
                }

                return $mini_plan->processing_charge > 0 ? ($price + $this->getIsnaadReturnHandling($order, $mini_plan)) : $price;
            } else {

                $orderWeight =ceil($order->order->weight) ;
                $price = $mini_plan->{'out_side_ryad' . $order_type};

                if ($orderWeight > $alloWeightInSA) {
                    $extraweight = $orderWeight -$alloWeightInSA;
                    $extraPrice = ($extraweight * $mini_plan->{'add_cost_in_sa' . $order_type});

                    $price = $price + $extraPrice;
                }


                return $mini_plan->processing_charge > 0 ? ($price + $this->getIsnaadReturnHandling($order, $mini_plan)) : $price;
            }


        } else {
            $mini_plan= $this->get_order_plan($order,$mini_plan,$numberOfStoreOrders,$hasManyPlan);

            return $this->InternationalShpping($order,$mini_plan ,false);
        }
    }

    public function exportIsnaadReturn($request, $flag)
    {
        $date = $request->from ? Carbon::create($request->from) : new Carbon();
        $first = Carbon::create($request->from)->day;
        $last = Carbon::create($request->to)->day;
        $order = isnaad_return::query()->where('active',1)->with('order', 'store', 'carrier');

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
            $data[$i]['date'] = $or->created_at->format('Y-m-d');
            $data[$i]['total_item'] = $or->order->Qty_Item ?? 0;

            $data[$i]['weight'] = $or->order->weight;
            $data[$i]['Total_Qty'] = $or->order->Qty_Item ?? 0;
            $data[$i]['Service_Type'] = 'Shipping: Client Return - Carrier & Transportation';
            $data[$i]['ID_Reg'] = $or->shipping_number;
            $data[$i]['desc'] = 'SHiPPING: Order#:' . $or->order_number . '-Carrier>' . $or->carrier->name . ':' . $or->traking_number;
            if ($or->order->country == 'SA') {
                $data[$i]['country'] = 'SA';
            } else {
                $data[$i]['country'] = $or->order->countries->is_gcc ? 'GCC' : $or->country;
            }

            $data[$i]['city'] = $or->order->city == 'Riyadh' ? 'in Riyadh ' : 'out Riyadh';;
            $data[$i]['extraWeightPrice'] = '';
            $data[$i]['Cost_Value'] =number_format($this->getIsnaadReturnCost($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']),2,'.', '') ;
            $data[$i]['tracking_number'] = '';
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, false), 'orders.xlsx') : $data;
    }

    public function InternationalShpping($order, $mini_plan,$flag = true)
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
            $data[$i]['Total_Qty'] = $or->order->Qty_Item ?? 0;
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
            $data[$i]['Cost_Value'] =number_format( $this->international_return($or),2,'.', '');

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
        $store = store::where('account_id', '=', $request->get('store'))->first();
        $hasMultiplePlan = $store->hasMultiplePlan;
        if($store->cr_id){
            $stores_id= store::select('account_id')->where('cr_id',$store->cr_id)->get()->pluck('account_id')->toArray();


        }else{
            $stores_id[]=$store->account_id;
        }

        if ($hasMultiplePlan) {


            $mini_plan = nstoreplan::query()->where('store_id', $request->get('store'))->with('cod_plan')->where('fromDate', '<', $request->to)->orderBy('fromDate', 'desc')->get();
            $numberOfStoreOrders = order::query()->where('active',1)->whereIn('store_id',$stores_id )->whereBetween('shipping_date', [$request->get('from'), $request->get('to')])->count();


            $hasMultiplePlan = true;


        } else {

            $mini_plan = masterPlan::query()->where('store_id', $request->get('store'))->orderBy('from_date', 'desc')->get();

        }


        return [
            'hasManyPlan' => $hasMultiplePlan,
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

        $to = Carbon::parse($request->to);
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
            $data[$i]['total_item'] = $stoage->sum_of_sin_volume;
            $data[$i]['weight'] = '0';
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

    public function DiscountExport($request, $flag = true, $pdfDis = false)
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
            $data[$i]['total_item'] = $discount->total_item;
            $data[$i]['weight'] = '0';
            $data[$i]['Total_Qty'] = $discount->total_item;
            $data[$i]['Service_Type'] = $pdfDis ? $discount->service_type : $discount->service_type_ser;

            $data[$i]['ID_Reg'] = '';
            $data[$i]['desc'] = $discount->description;
            $data[$i]['country'] = 'SA';
            $data[$i]['city'] = 'in Riyadh ';
            $data[$i]['extra'] = '';
            $data[$i]['Cost_Value'] = $discount->total_disccount;
            $data[$i]['tracking_number'] = '';
            $data[$i]['sr'] =  $discount->service_type ;
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
            $data[$i]['total_item'] = '1';
            $data[$i]['weight'] = '0';
            $data[$i]['Total_Qty'] = '1';
            $data[$i]['Service_Type'] = 'Another Expensive';
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

    public function getIsnaadReturnHandling($order, $mini_plan)
    {

        $order_type = $order->carrier == 'MORA' ? '_fr' : '';
        if ($order->created_at <= '2021-11-01') {
            return 0;
        }

        $price = $mini_plan->{'return_charge_out' . $order_type};

        if ($order->order->Qty_Item == 1) {

            return $price;
        } else {
            // dd($order->store->return_charge_each_extra);

            if (count(explode(',', $mini_plan->{'return_charge_each_extra' . $order_type})) == 1) {


                $extraQty = $order->order->Qty_Item - 1;
                //    dd($order->store->return_charge_each_extra);

                $extraPrice = $mini_plan->{'return_charge_each_extra' . $order_type} * $extraQty;

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


    public function get_total_new_invoice_report(Request $request)
    {
        $orders = $this->InvoiceReportDate($request, true);
        // $orders
    }

    public function pdfInstall(Request $request, $systemFee, $replenishment,
                                       $storage,
                                       $Handling,
                                       $Shipping,
                                       $Return,
                                       $IsnaadReturn,
                                       $InternationalReturn,
                                       $Transportaion,
                                       $ExtraCost,
                                       $Discount,
                                       $inv_num,
                                       $confirmed_invoice = false
    )
    {



        $data['Discount'] = collect($Discount);

        $data['cost']['IsnaadReturn'] = collect($IsnaadReturn)->sum('Cost_Value');

        $data['cost'] ['InternationalReturn'] = collect($InternationalReturn)->sum('Cost_Value') +$data['Discount']->where('sr', 8)->sum('Cost_Value');

        $data['cost']['Handling'] = collect($Handling)->sum('Cost_Value') + $data['Discount']->where('sr', 0)->sum('Cost_Value') ?? 0;

        $data['cost']['Shipping'] = (collect($Shipping)->sum('Cost_Value') + $data['cost'] ['InternationalReturn'] + $data['cost']['IsnaadReturn']) + $data['Discount']->where('sr', 2)->sum('Cost_Value') ?? 0;

        $data['cost']['RetrunHandling'] = collect($Return)->sum('Cost_Value') + $data['Discount']->where('sr', 6)->sum('Cost_Value') ?? 0;

        $data['cost']['Rep'] = collect($replenishment)->sum('Cost_Value') + $data['Discount']->where('sr', 4)->sum('Cost_Value') ?? 0;

        $data['cost']['System'] = collect($systemFee)->sum('Cost_Value') + $data['Discount']->where('sr', 5)->sum('Cost_Value') ?? 0;

        $data['cost'] ['Storage'] = collect($storage)->sum('Cost_Value') + $data['Discount']->where('sr', 9)->sum('Cost_Value') ?? 0;

        $data['cost'] ['Transportaion'] = collect($Transportaion)->sum('Cost_Value') +$data['Discount']->where('sr', 10)->sum('Cost_Value') ?? 0;

        $data['cost'] ['ExtraCost'] = collect($ExtraCost)->sum('Cost_Value');

        $store = store::query()->where('account_id', $request->store)->first();
        $file_name = $store->account_id . '_' . $request->from . '_' . $request->to . '.xlsx';
        // $invoice = invoice_excel::where('path', $file_name)->first();
        $data['inv_num'] = $inv_num;

        $data['cost']['general_discount'] = $data['Discount']->where('sr', 3)->sum('Cost_Value');


        unset($data['cost']['InternationalReturn']);
        unset($data['cost']['IsnaadReturn']);
        $data['total_cost'] = 0;
        $data['total_vat'] = 0;
        $data['total_total'] = 0;


        foreach ($data['cost'] as $key => $value) {
            if($key!='general_discount'){
                $data['total_cost'] += $value;
            }
            $data['vat'][$key] = ($value * .15);
            $data['total'][$key] = $data['vat'][$key] + $data['cost'][$key];
            if($key!='general_discount'){
                $data['total_vat'] += $data['vat'][$key];
            }
            if($key!='general_discount'){
                $data['total_total'] += $data['total'][$key];
            }
        }
        if($store->account_id==47 && Carbon::parse($request->from)->gt('2022-05-31')){
            $store->Name_ar='شركة الطريق المختصر للتسويق';
            $store->Tax_Number=311234757700003;
            $store->Register_number=1010423726;
            $store->Address='الرياض،العمل،سليمان بن مالك ،12991';
        }
        $data['store'] = $store;
        $data['start_date'] = $request->from;
        $data['end_date'] = $request->to;
        $data['due_total'] = $data['total_cost'] + $data['cost']['general_discount'];
        $data['due_discount'] = $data['total_vat'] + $data['vat']['general_discount'];
        $data['due_total_total'] = $data['total_total'] + $data['total']['general_discount'];


        $hourse = Carbon::now()->hour;
        $min = Carbon::now()->minute ;
        $data['printedDate']=Carbon::parse($request->to)->addDay()->setTime($hourse,$min)->format('Y-m-d g:i:s A');

        $value = \MPhpMaster\ZATCA\TagBag::make()
            ->tag(1, (string)'Isnaad Al-khaleejia Company')
            ->tag(2, (string)'310489620300003')
            ->tag(3, (string)$data['printedDate'])
            ->tag(4, (string)$data['total_total'])
            ->tag(5, (string)$data['total_vat'])
            ->toTLV();
        $value = base64_encode($value);
        if ($confirmed_invoice) {
            $file_name = '/confirmed/' . $inv_num . '.pdf';
        } else {
            $file_name = '/draf/' . $inv_num . '.pdf';
        }

        $data['qr'] = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', QrCode::format('svg')->generate($value));


        $pdf = \Meneses\LaravelMpdf\Facades\LaravelMpdf::loadView('newinvoice', $data);
        laravelStorage::disk('uploads')->put($file_name, $pdf->output());
        return [
            'file_name' => $file_name,
            'total_before_vat' => $data['due_total'] ,
            'total_after_vat' => $data['due_total_total'],
            'total_vat' => $data['due_discount'],
        ];
        /// return $flag ? $pdf->download('invoice.pdf') : ['file_name' => $file_name, 'inv_num' => $data['inv_num']];


    }

    private function getNewInvoiceNumber()
    {
        $lastInvoice = invoicies::latest('created_at')->first();
        if (!$lastInvoice) return 'DRA1';
        $newinvoiceNumber = (int)substr($lastInvoice->inv_number, 3) + 1;

        return 'DRA' . $newinvoiceNumber;


    }

    private function checkOverlapOn($request)
    {

        $invoice = confirm_invoice::whereHas('draft', function ($q) use ($request) {
            $q->where('store_id', $request->store)->Conflict($request->from, $request->to);
        })->first();

        if (!$invoice) {
            return false;
        }
        return $invoice;

    }

    public function confirmed(Request $request)
    {

        $draftInvoice = invoicies::findOrFail($request->id);


        $from = Carbon::parse($draftInvoice->from_date)->format('Y-m-d');
        $to = Carbon::parse($draftInvoice->to_date)->format('Y-m-d');
        $store = $draftInvoice->store_id;
        $request->request->add(['from' => $from]);
        $request->request->add(['to' => $to]);
        $request->request->add(['store' => $store]);
        $request->request->add(['draft_id' => $draftInvoice->id]);
        //   $request->request->add(['draft_id' => $draftInvoice->id]);
        $request->request->add(['system' => $draftInvoice->system]);

        $this->ExportAll($request, true);
        $draftInvoice->update([
            'confirmed' => 1
        ]);
        return response()->json([
            'success' => true
        ]);
    }


    public function UpdateConfermed(Request $request){
        $invoice=  confirm_invoice::with('draft')->findOrFail($request->id);

        $from = Carbon::parse($invoice->draft->from_date)->format('Y-m-d');
        $to = Carbon::parse($invoice->draft->to_date)->format('Y-m-d');

        $store = $invoice->draft->store_id;
        $request->request->add(['from' => $from]);
        $request->request->add(['to' => $to]);
        $request->request->add(['store' => $store]);
        $request->request->add(['draft_id' => $invoice->draft->id]);
        $request->request->add(['invoice' => $invoice]);
        //   $request->request->add(['draft_id' => $draftInvoice->id]);
        $request->request->add(['system' => $invoice->draft->system]);

        $this->ExportAll($request, false,true);

        return response()->json([
            'success' => true
        ]);
    }
    private function getNewConfirmInvoiceNumber()
    {
        $lastInvoice = confirm_invoice::latest('created_at')->first();

        return "ISN" . ($lastInvoice ? $lastInvoice ->id +1 :1448);

    }

    public function getTotal(Request $request){

        $data['Discount'] = collect($this->DiscountExport($request, false, true));

        $data['cost']['IsnaadReturn'] = collect($this->exportIsnaadReturn($request, false))->sum('Cost_Value');
        $data['cost'] ['InternationalReturn'] = collect($this->exportInternationalReturn($request, false))->sum('Cost_Value');
        $data['cost']['Handling'] = collect($this->HandlingPickExport($request, false))->sum('Cost_Value') + $data['Discount']->where('Service_Type', 0)->sum('Cost_Value') ?? 0;

        $data['cost']['Shipping'] = (collect($this->orderExportExcel($request, false))->sum('Cost_Value') + $data['cost'] ['InternationalReturn'] + $data['cost']['IsnaadReturn']) + $data['Discount']->where('Service_Type', 2)->sum('Cost_Value') ?? 0;

        $data['cost']['RetrunHandling'] = collect($this->exportReturnCharge($request, false))->sum('Cost_Value') + $data['Discount']->where('Service_Type', 6)->sum('Cost_Value') ?? 0;

        $data['cost']['Rep'] = collect($this->exportReplanchment($request, false))->sum('Cost_Value') + $data['Discount']->where('Service_Type', 4)->sum('Cost_Value') ?? 0;
        if($request->system){
            $data['cost']['System'] = collect($this->exportSystemFee($request, false))->sum('Cost_Value') + $data['Discount']->where('Service_Type', 5)->sum('Cost_Value') ?? 0;

        }

        $data['cost'] ['Storage'] = collect($this->StorageExport($request, false))->sum('Cost_Value') + $data['Discount']->where('Service_Type', 9)->sum('Cost_Value') ?? 0;

        $data['cost'] ['Transportaion'] = collect($this->TransportaionExport($request, false))->sum('Cost_Value');
        $data['cost'] ['ExtraCost'] = collect($this->ExtraCostExport($request, false))->sum('Cost_Value');


        $data['cost']['general_discount'] = $data['Discount']->where('Service_Type', 3)->sum('Cost_Value');


        unset($data['cost']['InternationalReturn']);
        unset($data['cost']['IsnaadReturn']);
        $data['total_cost'] = 0;
        $data['total_vat'] = 0;
        $data['total_total'] = 0;


        foreach ($data['cost'] as $key => $value) {
            $data['total_cost'] += $value;
            $data['vat'][$key] = ($value * .15);
            $data['total'][$key] = $data['vat'][$key] + $data['cost'][$key];
            $data['total_vat'] += $data['vat'][$key];
            $data['total_total'] += $data['total'][$key];
        }

        $data['due_total'] = $data['total_cost'] + $data['cost']['general_discount'];
        $data['due_discount'] = $data['total_vat'] + $data['vat']['general_discount'];
        $data['due_total_total'] = $data['total_total'] + $data['total']['general_discount'];

        return response()->json([
            'total'=> $data['due_total_total'],
            'due_discount'=> $data['due_discount'],
            'due_total'=>$data['due_total']
        ]);
    }
    public function get_cod_plan($order,$plan ){
        try{
            $plan= $plan->cod_plan->where('from_num', '<', $order->cod_amount)->where('to_num', '>=', $order->cod_amount)->first();
            return $plan->cod;
        }catch(\Exception $t){
            dd($order,'testset');
        }

    }
    public function get_order_plan($order,$plans,$numberOfStoreOrders,$hasManyPlan){

        if($hasManyPlan){
            $mini_plan = $plans->where('fromDate', '<=', $order->shipping_date)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

        }else{
            $mini_plan = $plans->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();
        }

        return $mini_plan;
    }

}
