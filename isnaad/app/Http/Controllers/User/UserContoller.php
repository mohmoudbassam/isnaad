<?php


namespace App\Http\Controllers\User;

use App\Models\masterPlan;
use App\Models\nstoreplan;
use App\statment;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Yajra\DataTables\DataTables;
use App\Exports\ClientExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\isnaad_return;
use App\Exports\insaadReturn;
use function GuzzleHttp\Promise\all;

class UserContoller extends Controller
{
    public function account_setting()
    {
        return view('newDesign.mainPage.accountSetting');
    }

    public function updateInfo(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if (auth()->user()->update($request->all())) {
            return redirect()->back()->with('suc', 'updated successfully');
        } else {
            return redirect()->back()->with('suc', 'pleas try again');
        }

    }

    public function update_password(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => 'required',
        ]);
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['old Password incorrect']);
        } else {
            $validatedData = $request->validate([
                'password' => 'required|confirmed|min:8',
            ]);
        }

        $new_passowrd = bcrypt($request->password);

        $password = auth()->user()->update(['password' => $new_passowrd]);
        return redirect()->back()
            ->with('passSuc', 'Password updated successfully');
    }

    public function client_view()
    {
        return view('m_design.client');
    }

    public function getClentData(Request $request)
    {
        $client = user::query();
        $client->where('type', 'a');
        return Datatables::of($client->with('store'))
            ->make(true);
    }

    public function getTrakingUrl($order_number)
    {
        $date = ['order_number' => $order_number];

        $validate = validator($date, [
            'order_number' => 'required|numeric',

        ]);
        if ($validate->fails()) {
            return view('notFound');
        }
        $order = order::where([['order_number', $order_number], ['active', '1']])->first();
        if ($order) {
            return Redirect::to($order->carriers->tracking_link . $order->tracking_number);
        } else {
            return view('notFound');
        }

    }

    public function saveClient(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'contact_person' => 'required',
            'shipping_charge_in_ra' => 'required',
            'shipping_charge_out_ra' => 'required',
            'add_cost_out_sa' => 'required',
            'add_cost_in_sa' => 'required',
            'cod_charge' => 'required',
            'weight_in_sa' => 'required',
            'weight_out_sa' => 'required',
            'website' => 'required',
        ]);

        $user = user::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => 'a'

        ]);
        $data = $request->all();
        //dd($data);
        unset($data['password']);
        $data['user_id'] = $user->id;
        $data['status'] = 1;
        store::create($data);
    }

    public function addClient()
    {
        return view('newDesign.mainPage.addClient');

    }

    public function ExportClient(Request $request)
    {

        $listOfMonth = $this->getMonthListFromDate();
        $stores = store::all();

        $i = 0;
        foreach ($stores as $st) {

            $data[$i]['name'] = $st->name;
            $data[$i]['contact_person'] = $st->contact_person;
            $data[$i]['phone'] = $st->phone;
            $data[$i]['website'] = $st->website;
            $data[$i]['email'] = $st->email;
            $data[$i]['Order_Number'] = $st->order_number;
            $this->getMonthOrders($data, $i, $st->Order_Number_Per_Month, $listOfMonth);
            $i++;
        }

        return Excel::download(new ClientExport($data, $listOfMonth), 'stores.xlsx');
    }

    public function getMonthListFromDate()
    {

        $periods = CarbonPeriod::create('2020-04-01', '1 month', Carbon::now()->format('Y-m-d'));
        $months = array();
        foreach ($periods as $period) {

            $months[] = $period->format("Y-m");
        }

        return $months;
    }

    public function getMonthOrders(&$data, $i, $stMonth, $listOfMonth)
    {
        foreach ($listOfMonth as $list) {
            if ($mm = $stMonth->where('monthYear', $list)->first()) {
                $data[$i][$list] = $mm->numberOfOrder;
            } else {
                $data[$i][$list] = 0;
            }
        }
    }

    public function client_return()
    {
        $stores = store::all();
        $carriers = \App\carrier::all();
        $account_mangers = user::query()->whereIn('id', store::select('account_manger')->whereNotNull('account_manger')->groupBy('account_manger')->get()->pluck('account_manger'))->get();

        return view('m_design.isnnad_client.return', ['stores' => $stores, 'carriers' => $carriers, 'account_mangers' => $account_mangers]);
    }


    public function client_return_date(Request $request, $flage = false)
    {
        $date = new Carbon($request->from);
        $month = $date->month;


        $date_type = $request->get('date_type');
        $orders = isnaad_return::query()->with(['store', 'carrier'])->whereHas('order', function ($q) {
            $q->whereNotNull('shipping_date');
        })->where('active', 1);

        $orders = $orders->when($request->billed, function ($query) use ($request) {
            if ($request->billed == 1) {
                return $query->whereNotNull('inv_num');
            } else {
                return $query->whereNull('inv_num');
            }
        });
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->add(new DateInterval('P1D'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $orders->whereBetween($date_type, [$from, $to]);
            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');

                $to = Carbon::now();
                $to = $to->add(new DateInterval('P1D'));
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween($date_type, [$from, $to]);

            }
        }
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }
        if ($request->has('status') && $request->get('status') != '') {
            $orders->where('status', $request->get('status'));
        }
        if ($request->has('carierrs') && $request->get('carierrs') != '' && !empty($request->get('carierrs'))) {

            if (is_array($request->carierrs)) {
                $orders = $orders->whereIn('carrier_id', $request->carierrs);
            } else {
                $orders = $orders->where('carrier_id', $request->carierrs);
            }

        }

        if ($request->has('place') && $request->get('place') != '') {
            if ($request->get('place') == 0) {
                $orders = $orders->whereHas('order', function ($q) {
                    return $q->where('country', '!=', 'SA');
                });
            } else {
                $orders = $orders->whereHas('order', function ($q) {
                    return $q->where('country', '=', 'SA');
                });
            }
        }
        return $flage ? $orders->with('order') : DataTables::of($orders)
            ->addColumn('cost', function ($order) use ($request) {
                $mini_plans = $this->get_order_plan($order, $request);
                return $this->getIsnaadReturnCost($order, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
            })
            ->addColumn('inv', function ($order) use ($request) {
                if ($order->inv_num) {
                    $statment = statment::where('inv', 'ISN' . $order->inv_num)->first();
                    return '<a target="_blank" href="' . url("ne-show-statment/$statment->id") . '">ISN'  . $order->inv_num . '</a>';
                }
            })->rawColumns(['inv'])
            ->make(true);

    }

    public function export_return_date(Request $request)
    {

        $orders = $this->client_return_date($request, true);
        $orders = $orders->get();

        $data = [];
        $i = 0;
        $date = new Carbon($request->from);
        $year = $date->year;
        $month = substr($date->monthName, 0, 3);

        foreach ($orders as $or) {

            // $data[$i]['shipping_number'] = $or->order->shipping_number;
            $mini_plans = $this->get_order_plan($or, $request);
            $shipping_price=$this->getIsnaadReturnCost($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']);
            $data[$i]['order_number'] = $or->order->order_number ?? '';
            $data[$i]['shipping_number'] = $or->order->shipping_number ?? '';
            $data[$i]['carrier'] = $or->carrier->name ?? '';
            $data[$i]['tracking_number'] = $or->traking_number ?? '';
            $data[$i]['store'] = $or->store->name ?? '';
            $data[$i]['total_item'] = $or->order->Qty_Item ?? '';
            $data[$i]['payment'] ='paid';
            $data[$i]['cod_amount'] ='';
            $data[$i]['city'] = $or->order->city ?? '';
            $data[$i]['country'] = $or->order->country ?? '';
            $data[$i]['status'] = $or->status ?'client deliverd':'client intransit';
            $data[$i]['weight'] = $or->order->weight ?? '';
            $data[$i]['shipping_date'] =  $or->created_at->format('Y-m-d') ?? '';
            $data[$i]['delivary_date'] = Carbon::parse($or->delivred_date)->format('Y-m-d');
            $data[$i]['carrier_charge'] = $or->order->carrier_charge ?? '';
            $data[$i]['shipping_price'] =$this->getIsnaadReturnCost($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders']) ?? '';
            $data[$i]['diff'] =$this->getIsnaadReturnCost($or, $mini_plans['plans'], $mini_plans['hasManyPlan'], $mini_plans['numberOfStoreOrders'])-$or->order->carrier_charge ;
            $data[$i]['inv'] =$or->inv_num ? 'ISN'.$or->inv_num:'';
            $data[$i]['order_created'] = $or->created_at ?? '';
            $i++;

        }
        return Excel::download(new insaadReturn($data), 'isnaadReturn.xlsx');
    }

    public function make_deleved_client_return()
    {
        return view('m_design.isnnad_client.make_deliverd');
    }

    public function make_deleved_client_return_Action(Request $request)
    {
        $request->validate(['shipping_number' => 'required']);
        $order = isnaad_return::query()->where('shipping_number', $request->get('shipping_number'))->first();

        if (!$order) {
            return redirect()->back()->with('error', 'this order not found');
        }

        $order->update(['status' => 1, 'delivred_date' => Carbon::now()]);

        return redirect()->back()->with('success', 'order updated successfully');
    }


    public function getIsnaadReturnCost($order, $mini_plans, $hasManyPlan, $numberOfStoreOrders)
    {
        $mini_plan = $mini_plans;
        $order_type = $order->carrier == 'MORA' ? '_fr' : '';

        try {
            $alloWeightInSA = $hasManyPlan ? $mini_plan->{'allow_wight_sa' . $order_type} : $mini_plan->{'allowed_weight_in_sa' . $order_type};

        } catch (\Throwable $t) {
            return 0;
        }
        if ($order->order->country == 'SA') {

            if ($order->order->city == 'Riyadh') {

                $orderWeight = ceil($order->order->weight);
                $price = $mini_plan->{'in_side_ryad' . $order_type};

                if ($orderWeight > $alloWeightInSA) {

                    $extraweight = $orderWeight - $alloWeightInSA;
                    $extraPrice = ($extraweight * $mini_plan->{'add_cost_in_sa' . $order_type});

                    $price = $price + $extraPrice;
                }

                return $mini_plan->processing_charge > 0 ? ($price + $this->getIsnaadReturnHandling($order, $mini_plan)) : $price;
            } else {

                $orderWeight = ceil($order->order->weight);
                $price = $mini_plan->{'out_side_ryad' . $order_type};

                if ($orderWeight > $alloWeightInSA) {
                    $extraweight = $orderWeight - $alloWeightInSA;
                    $extraPrice = ($extraweight * $mini_plan->{'add_cost_in_sa' . $order_type});

                    $price = $price + $extraPrice;
                }


                return $mini_plan->processing_charge > 0 ? ($price + $this->getIsnaadReturnHandling($order, $mini_plan)) : $price;
            }


        } else {

            return $this->InternationalShpping($order, $mini_plan, false);
        }
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


    public function get_order_plan($order, $request)
    {

        $numberOfStoreOrders = 0;
        $store = $order->store;

        $hasMultiplePlan = $store->hasMultiplePlan;

        if ($store->cr_id) {
            $stores_id = store::select('account_id')->where('cr_id', $store->cr_id)->get()->pluck('account_id')->toArray();
        } else {
            $stores_id[] = $store->account_id;
        }

        if ($hasMultiplePlan) {

            $mini_plan = nstoreplan::query()->where('store_id', $order->store->account_id)->with('cod_plan')->where('fromDate', '<', $order->created_at->toDateString())->orderBy('fromDate', 'desc')->get();
            $from = Carbon::parse($order->created_at)->startOfMonth()->format('Y-m-d');
            $to  = Carbon::parse($order->created_at)->endOfMonth()->format('Y-m-d');

            $numberOfStoreOrders = order::query()->where('active', 1)->whereIn('store_id', $stores_id)->whereBetween('shipping_date', [$from, $to])->count();
            $mini_plan = $mini_plan->where('fromDate', '<=', $order->created_at->toDateString())->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>', $numberOfStoreOrders)->first();

            $hasMultiplePlan = true;


        } else {

            $mini_plan = masterPlan::query()->where('store_id', $order->store->account_id)->orderBy('from_date', 'desc')->get();

            $mini_plan = $mini_plan->where('from_date', '<=', Carbon::parse($order->created_at->toDateString())->format('Y-m-d'))->first();
        }


        return [
            'hasManyPlan' => $hasMultiplePlan,
            'plans' => $mini_plan,
            'numberOfStoreOrders' => $numberOfStoreOrders
        ];
    }
}
