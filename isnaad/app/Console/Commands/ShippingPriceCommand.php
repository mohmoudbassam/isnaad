<?php

namespace App\Console\Commands;

use App\Models\masterPlan;
use App\Models\nstoreplan;
use App\Models\shipping_price_jobs;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ShippingPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ShippingPrice:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command store the shipping price in database after conferming invoice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    $job=    shipping_price_jobs::first();
    $plan=  $this->checkStorePlans($job->store_id,$job->from_date,$job->to_date);
        $orders=order::query();

        $orders = $orders->select('id','order_status','shipping_price', 'chargalbeWeight', 'shipping_number',  'cod_amount', 'store_id', 'Qty_Item', 'country', 'city', 'shipping_date', 'created_at')
            ->where('carrier', '!=', 'Shipox')->where('store_id',$job->store_id)->where('country','SA')
            ->with(['countries', 'store:id,account_id,name,allow_wight_gcc'])
            ->whereBetween('shipping_date', [$job->from_date, $job->to_date])->Active()->get();


        foreach ($orders as $order) {

          $shipping=  $this->ShippingPrice($order,$job->from_date,$plan['plans'],$plan['hasManyPlan'],$plan['numberOfStoreOrders']);

            $order->update([
              'shipping_price'=>round($shipping['tatal_cost'],2)
          ]);
        }
    }

    public function checkStorePlans($store_id,$from ,$to)
    {
        $numberOfStoreOrders = 0;
        $hasManyPlan = store::where('account_id', '=', $store_id)->first()->hasMultiplePlan;

        if ($hasManyPlan) {

            $mini_plan = nstoreplan::where('store_id', $store_id)->where('fromDate', '<', $to)->orderBy('fromDate', 'desc')->get();
            $numberOfStoreOrders = order::where('store_id', $store_id)->whereBetween('created_at', [$from, $to])->count();
            $hasManyPlan = true;

        } else {

            $mini_plan = masterPlan::where('store_id', $store_id)->orderBy('from_date', 'desc')->get();

        }


        return [
            'hasManyPlan' => $hasManyPlan,
            'plans' => $mini_plan,
            'numberOfStoreOrders' => $numberOfStoreOrders
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
        if($hasManyPlan){
            $mini_plan = $mini_plan->where('fromDate', '<', $order->created_at)->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

        }else{
            $mini_plan = $mini_plan->where('from_date', '<=', Carbon::parse($order->shipping_date)->format('Y-m-d'))->first();

        }

            //   dd($mini_plan);
            $allow_wight_sa = $mini_plan->{'allowed_weight_in_sa' . $order_type};

            $add_cost_in_sa = $mini_plan->{'add_cost_in_sa' . $order_type};
            $cod_charge = $mini_plan->{'cod_charge' . $order_type};

            if ($order->country == 'SA') {
                if ($order->city == 'Riyadh') {
                    $shipping_charge_in_ra = $mini_plan->{'in_side_ryad' . $order_type};

                    $price = $shipping_charge_in_ra;

                    if ($orderWeight > $allow_wight_sa) {

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
}
