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
use App\Models\masterPlan;
use App\Models\nstoreplan;
use App\order;
use App\store;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use App\Exports\OrderExportInoiceReport;


class DifferentReport extends Controller
{
    public static $suc = 0;

    public function index()
    {
        $stores = store::all();
        $carreires = carrier::all();
        return view('m_design.difference.index', ['stores' => $stores, 'carreires' => $carreires]);
    }

    public function list(Request $request ,$flag=false)
    {
        $orders = order::query()->with('store');
        if ($request->carrier) {
            $orders->where('carrier', $request->carrier);
        }

        if ($request->has('to')  != '' && $request->get('to') != '')
        {

            $to = new \DateTime($request->get('to'));

                $to = $to->add(new DateInterval('P1D'));


            $to = $to->format('Y/m/d');
            $from = new \DateTime($request->get('from'));
            $from = $from->format('Y/m/d');

            $orders = $orders->whereBetween('shipping_date', [$from, $to]);
        }

       $orders=  $orders->orderByDesc('created_at');
        $stores=clone $orders;
        $stores= $stores->select('store_id')->groupBy('store_id')->get()->pluck('store_id');

        $all_plans=$stores->map(function ($store) use ($request){
           return  $this->checkStorePlans($store,$request->from,$request->to);
        });

        if($flag){
            return $orders;
        }else{
            return Datatables::of($orders)
                ->addColumn('shipping_price',function($order)use($request,$all_plans){
                    $store_plan=[];
                    foreach ($all_plans as $plan) {
                        if($plan['store_id'] ==$order->store_id ){
                            $store_plan=$plan;
                        }
                    }
                    return $this->ShippingPrice($order,$request->get('from'),$store_plan['plans'],$store_plan['hasManyPlan'],$store_plan['numberOfStoreOrders'])['tatal_cost']  ;
                })
                ->make(true);
        }

    }


    public function checkStorePlans($store_id,$from,$to)
    {
        $numberOfStoreOrders = 0;
        $hasManyPlan = store::where('account_id', '=', $store_id)->first()->hasMultiplePlan;

        if ($hasManyPlan) {

            $mini_plan = nstoreplan::where('store_id', $store_id)->where('fromDate', '<', $to)->orderBy('fromDate', 'desc')->get();
            $numberOfStoreOrders = order::where('store_id', $store_id)->whereBetween('created_at', [$from, $to])->count();
            $hasManyPlan = true;

        } else {
            $mini_plan = masterPlan::where('store_id',$store_id)->orderBy('from_date', 'desc')->get();
        }


        return [
            'hasManyPlan' => $hasManyPlan,
            'plans' => $mini_plan,
            'numberOfStoreOrders' => $numberOfStoreOrders,
            'store_id'=>$store_id
        ];
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
            try{
    $allow_wight_sa = $mini_plan->{'allowed_weight_in_sa' . $order_type};
}catch (\Exception $e){
    
    dd($mini_plan,$order);
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
                else {
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

    public function orderExportExcel(Request $request, $flag = true)
    {
        $orders = order::query()->where('store_id','!=',74)->where('store_id','!=',72)->with('store');

        if ($request->carrier) {
            $orders->where('carrier', $request->carrier);
        }

        if ($request->has('to')  != '' && $request->get('to') != '')
        {

            $to = new \DateTime($request->get('to'));
            if ($request->get('dateType') == 0) {
                $to = $to->add(new DateInterval('P1D'));
            }

            $to = $to->format('Y/m/d');
            $from = new \DateTime($request->get('from'));
            $from = $from->format('Y/m/d');

            $orders = $orders->whereBetween('shipping_date', [$from, $to]);
        }

        $orders=  $orders->orderByDesc('created_at');
        $stores=clone $orders;
        $stores= $stores->select('store_id')->where('store_id','!=',74)->where('store_id','!=',72)->groupBy('store_id')->get()->pluck('store_id');

        $order = $orders->get();
        $all_plans=$stores->map(function ($store) use ($request){
            return  $this->checkStorePlans($store,$request->from,$request->to);
        });


        $data = [];
        $i = 0;

        foreach ($order as $or) {
            $store_plan=[];
            foreach ($all_plans as $plan) {
                if($plan['store_id'] ==$or->store_id ){
                    $store_plan=$plan;
                }
            }
            try{
 $cost = $this->ShippingPrice($or, $request->from, $store_plan['plans'], $store_plan['hasManyPlan'], $store_plan['numberOfStoreOrders']);
            }catch(\Exception $e){
dd($store_plan,$or);
            }
           

            $extraWeightPrice = $cost['extraweightPrice'];
            $total_cost = $cost['tatal_cost'];

            if (isset($or->store)) {
                $data[$i]['store'] = $or->store->name;
            } else {
                $data[$i]['store'] = '';
            }
            $data[$i]['statment_name'] ='teste';
            $data[$i]['date'] = $or->shipping_date;
            $data[$i]['total_item'] = 1;
            $data[$i]['weight'] = $or->country == 'SA' ? ceil($or->weight) : $or->chargalbeWeight;
            $data[$i]['Total_Qty'] = $or->Qty_Item;
            $data[$i]['Service_Type'] = 'shipping ';
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
            $data[$i]['cod_amount'] = $or->cod_amount;
            $i++;
        }

        return $flag == true ? Excel::download(new OrderExportInoiceReport($data, true), 'orders.xlsx') : $data;
    }
}
