<?php

namespace App\Http\Controllers\Reports;
use App\carrier;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Jobs\InsaadReport;
use App\Mail\CompleteExportedFile;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Neodynamic\SDK\Web\WebClientPrint;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use DateInterval;

use Session;
class OrderReports extends Controller
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
       // return view('m_design.Reports.new_isnaad_report', $array);
    }

    public function OrderReportsData(Request $request, $flag = false)
    {
        $orders = order::query()
            ->select('tracking_number',
                'cod_amount', 'created_at', 'awb_url',
                'store_id', 'order_status',
                'delivery_date', 'city', 'fname','phone','carrier_charge','shipping_date', 'address_1', 'weight', 'order_number', 'Last_Status', 'shipping_number', 'carrier', 'country', 'Qty_Item'
                ,'chargalbeWeight','actulWeight','isnaad_return_date'
            );
        $orders = $orders->where('active', '=', '1');
        if ($request->has('place') && $request->get('place') != '') {

            if ($request->get('place') == 1) {
                $orders = $orders->where('country', '!=', 'SA');
            } elseif($request->get('place') == 2){
                $orders = $orders->where('country', '=', 'SA');
            }elseif($request->get('place') == 3){
                $orders = $orders->where([['country', '=', 'SA'],['city','=','Riyadh']]);
            }elseif($request->get('place') == 4){
                $orders = $orders->where([['country', '=', 'SA'],['city','!=','Riyadh']]);
            }
        }
        if ($request->has('carierrs') && $request->get('carierrs') != '' && !empty( $request->get('carierrs'))) {

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
                $datetype='delivery_date';
            }elseif (is_array($request->get('status'))||$request->get('status')=='Returned'){

                if(array_search('Returned',$request->get('status'))){

                    $datetype='return_date_carrier';
                }

            }elseif ($request->get('dateType') == 2){

                $datetype='return_date_carrier';
            }elseif ($request->get('dateType') == 3){

                $datetype='shipping_date';
            }else{
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
        //  dd($datetype);
        if ($request->has('store') && $request->get('store') != '') {

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }
        if ($request->has('status') && $request->get('dateType')!=2) {

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


        if ($flag) {
            return $orders;

        } else {
            $cod_amount_total=  $orders->sum('cod_amount');

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
                })->addColumn('cod_total', function ($order) use ($cod_amount_total) {
                    return $cod_amount_total;
                })->addColumn('shipping_price', function ($order) use ($cod_amount_total) {

                    if($order->country != 'SA'){

                        $shippingCharge=  $this->ShippingPrice($order,'');

                        return  $shippingCharge['tatal_cost'];
                    }
                    return $this->ShippingPrice($order,'');
                })
                ->rawColumns(['enable', 'sh_date', 'status','cod_total','shipping_price'])
                ->make(true);

        }


    }

    public function orderExportExcel(Request $request)
    {
        $data = [];
        $i = 0;
        $order = $this->OrderReportsData($request, true);
        // dd($order->get()->first());


        $order = $this->OrderReportsData($request, true);
        $order = $order->with('store')->get();
        if(is_array($request->get('status'))){
            if(in_array('Returned',$request->get('status'))){
                $has_return=true;
            }  else{
                $has_return=false;
            }
        }else{
            if($request->get('status')=='Returned'){
                $has_return=true;
            }else{
                $has_return=false;
            }
        }
        $data = [];
        $i = 0;
        $cods = 0;
        foreach ($order as $or) {

            $shippingCharge= $this->ShippingPrice($or, '');

            if($or->country != 'SA'){
                try{
                    $shippingCharge= is_array($shippingCharge) ?$shippingCharge['tatal_cost']:$shippingCharge ;
                }catch(\Throwable $t){

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
            $data[$i]['cod_amount'] = $or->order_status=='Returned'?0:$or->cod_amount;


            $data[$i]['name'] = $or->fname;
            $data[$i]['address'] = $or->address_1;
            $data[$i]['phone'] = $or->phone;
            $data[$i]['city'] = $or->city;
            $data[$i]['country'] = $or->country;
            $data[$i]['order_status'] = $or->order_status;
            $data[$i]['Last_Status'] = $or->Last_Status;
            $data[$i]['weight'] = $or->weight;
            $data[$i]['shipping_date'] = $or->shipping_date;
            $data[$i]['delivery_date'] =$or->order_status == 'Returned' ? $or->isnaad_return_date :$or->delivery_date;
            $data[$i]['comment'] = $or->Comments;

            $data[$i]['return_date_carrier'] = $or->return_date_carrier;
            $data[$i]['chargalbeWeight'] = $or->chargalbeWeight;
            $data[$i]['actulWeight'] = $or->actulWeight;
            if(auth()->user()->hasPermissionTo('carrier_charge_view')){
                $data[$i]['carrier_charge'] = $or->carrier_charge;
            }

            if (auth()->user()->hasPermissionTo('isnaarReport_shippingPrice')){
                $data[$i]['shipping_price'] =$shippingCharge;
            }
            if (auth()->user()->hasPermissionTo('isnaarReport_diff')){

                $data[$i]['diff'] = $shippingCharge - $or->carrier_charge;



            }
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
        $data[$i + 1]['name'] = '';
        $data[$i + 1]['address'] = '';
        $data[$i + 1]['phone'] = '';
        $data[$i + 1]['city'] = '';
        $data[$i + 1]['country'] = '';
        $data[$i + 1]['order_status'] = '';
        $data[$i + 1]['Last_Status'] = '';
        $data[$i + 1]['weight'] = '';
        $data[$i + 1]['shipping_date'] = '';
        $data[$i + 1]['delivery_date'] = '';
        $data[$i + 1]['comment'] = '';
        $data[$i + 1]['created_at'] = '';
        $data[$i + 1]['cod_amount'] = $cods;

        return Excel::download(new orderExport($data), 'orders.xlsx');
    }

    public function orderExportPdf(Request $request)
    {

        $order = $this->OrderReportsData($request, true);
        $order = $order->get();
        $data = [];
        $i = 0;
        foreach ($order as $or) {
            $data[$i]['shipping_number'] = $or->shipping_number;
            $data[$i]['order_number'] = $or->order_number;
            $data[$i]['carrier'] = $or->carrier;
            $data[$i]['cod_amount'] = $or->order_status=='Returned'?0:$or->cod_amount;
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
            $data[$i]['cod_amount'] = $or->cod_amount;
            $data[$i]['name'] = $or->fname;
            $data[$i]['address'] = $or->address_1;
            $data[$i]['phone'] = $or->phone;
            $data[$i]['city'] = $or->city;
            $data[$i]['country'] = $or->country;
            $data[$i]['order_created'] = $or->created_at;
            $data[$i]['shipping_date'] = $or->shipping_date;
            $data[$i]['delivery_date'] = $data[$i]['delivery_date'] =$or->order_stauts=='Returned'?$or->isnaad_return_date :$or->delivery_date;;
            $data[$i]['created_at'] = $or->created_at;

            $data[$i]['Return'] = $or->return_date_carrier;

            $i++;
        }

        return Excel::download(new orderExport($data), 'orders.pdf');
    }

    public function orders_interrupted(Request $request)
    {
        $inter= interrupted_orders::query();
        if($request->international==1){
            $inter->where([['country','!=','SA'],['issue','International Order']]);
        }
        return Datatables::of($inter)
            ->make(true);
    }
    public function ShippingPrice($order, $from)
    {

        $date = new Carbon($from);

        if ($order->carrier == 'Pick') {
            return 5;
        }
        try {
            $order->store->hasPlan->count();
        }catch(\Exception $e){
            dd($order);
        }
        if ($order->store->hasPlan->count() != 0) {
            if (self::$store == null || $order->store->name != self::$store) {
                self::$store = $order->store->name;
                self::$count = $order->store->count_per_month;
            }

            $plan = $order->store->hasPlan->where('from_num', '<', self::$count)->where('to_num', '>', self::$count)->first();
            if(!$plan){
                $plan=  $order->store->hasPlan->first();
            }
            if ($order->country == 'SA') {

                if ($order->city == 'Riyadh') {
                    $price = $plan->in_side_ryad;

                } else {
                    try {
                        $price = $plan->out_side_ryad;

                    }catch (\Throwable $e){
                        dd($plan, $order->store, $order->store->count_per_month);
                    }

                }
                if ($order->cod_amount > 0 && $order->order_status !='Returned') {
                    $price += $plan->cod;
                }
                if (ceil($order->weight) > $plan->allow_wight_sa) {
                    $extraweight = ceil($order->weight) - $plan->allow_wight_sa;
                    $price = $price + ($extraweight * $plan->extra_wight_ksa);
                }

            } else {
                $price = $this->InternationalShpping($order);
            }
        } else {

            if ($order->country == 'SA') {
                if ($order->city == 'Riyadh') {
                    $price = $order->store->shipping_charge_in_ra;
                    if (ceil($order->weight) > $order->store->weight_in_sa) {
                        $extraweight =ceil($order->weight)   - $order->store->weight_in_sa;

                        $price = $price + ($extraweight * $order->store->add_cost_in_sa);
                    }

                    if ($order->cod_amount > 0 &&$order->order_status !='Returned') {
                        if($order->store->cod_charge < 1){
                            // dd($price,$order->cod_amount,$order->store->cod_charge);
                            $price =  $price+($order->cod_amount*$order->store->cod_charge);
                        }else{
                            $price += $order->store->cod_charge;
                        }

                    }

                } else {
                    $price = $order->store->shipping_charge_out_ra;
                    if (ceil($order->weight )> $order->store->weight_in_sa) {
                        $extraweight = ceil($order->weight ) - $order->store->weight_in_sa;
                        $price = $price + ($extraweight * $order->store->add_cost_in_sa);
                    }
                    if ($order->cod_amount > 0) {
                        if($order->store->cod_charge < 1){
                            $price =  $price+($order->cod_amount*$order->store->cod_charge);

                        }else{
                            $price += $order->store->cod_charge;
                        }
                    }

                }
            } else {


                $price = $this->InternationalShpping($order);
            }
        }

        return  $price;



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


            }
            else {

                return [
                    'tatal_cost' => $price,
                    'extraweightPrice' => 0
                ];


            }

        }
        else {

            $last_price =$order->shipping_date > '2021-09-30' ? $order->countries->first_half_october  : $order->countries->first_half ;
            $anotherHalf=$order->shipping_date > '2021-09-30' ? $order->countries->each_aditional_afte_half_october  : $order->countries->each_aditional_afte_half ;
            if ($orderWeight < .5) {

                return [
                    'tatal_cost' => round($last_price + ($last_price * $dis), 2),
                    'extraweightPrice' => 0
                ];


            }else{

                if($orderWeight > .5){
                    $extraweight = $orderWeight - .5;
                    $extraPrice = ($extraweight / .5) *$anotherHalf ;

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
