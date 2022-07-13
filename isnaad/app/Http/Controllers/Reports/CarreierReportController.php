<?php

namespace App\Http\Controllers\Reports;

use App\carrier_city;
use App\carrier;
use App\Exports\CarrierReportExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;
use App\Exports\OrderExportInoiceReport;
use DateInterval;

class CarreierReportController extends Controller
{
    public function index()
    {
        $carreires = carrier::all();
        $cities = carrier_city::all();
        $store = store::all();
        return view('m_design.Reports.carrier_report', ['carreires' => $carreires, 'cities' => $cities, 'stores' => $store]);
    }

    public function get_carriers_report(Request $request, $flag = false)
    {
  //  dd($request->all());
        $CarrierDeliverd = array('Mkhdoom',
            'Fourun',
            'SAMA',
            'Wadha',
            'FDA','Aramex');
        $orders = order::query();
        $orders->where('active', '=', '1');
        if ($request->has('carrier') && $request->get('carrier') != '') {


            $orders = $orders->where('carrier', $request->carrier);


        }
        if ($request->has('from') && $request->get('from') != '') {

            $datetype = $request->get('dateType') == 0 ? 'created_at' : 'delivery_date';

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                if ($request->get('dateType') == 0) {
                    $to = $to->add(new DateInterval('P1D'));
                }
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $orders = $orders->whereBetween($datetype, [$from, $to]);

            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $orders = $orders->whereBetween($datetype, [$from, $to]);
            }
        }
        if ($request->get('dateType') == 1) {
        //    dd($request->get('dateType'));
            $orders = $orders->where('order_status', 'Delivered');
        }

        if ($flag) {
            $orders = $orders->get();

            return $orders;
        } else {

            if ( $request->get('carrier') == null) {

                $orders = $orders->where([['carrier', 'Mkhdoom'], ['order_status', 'Delivered']])
                    ->orWhere([['carrier', 'Fourun'], ['order_status', 'Delivered']])
                    ->orWhere([['carrier', 'SAMA'], ['order_status', 'Delivered']])
                    ->orWhere([['carrier', 'Wadha'], ['order_status', 'Delivered']])
                    ->orWhere([['carrier', 'FDA'], ['order_status', 'Delivered']])
                    ->orWhere([['carrier', 'Tamex']])
                    ->orWhere([['carrier', 'Aramex']])
                    ->orWhere([['carrier', 'Aymakan']])
                    ->orWhere([['carrier', 'Zajil']])
                    ->orWhere([['carrier', 'Smsa']])
                    ->orWhere([['carrier', 'BARQ']]);

            } else {
               // dd('sd');
               if(array_search($request->carrier,$CarrierDeliverd)){
                   $orders->where([['carrier',$request->carrier],['order_status', 'Delivered']]);
                }else{
                   $orders->where([['carrier',$request->carrier]]);
               }
            }
            return Datatables::of($orders->with('carriers'))
                ->addColumn('shiping_charge', function ($order) {
                   return $order->shipping_charge;
                })->addColumn('cod_charge', function ($order) {
                    return $this->Cod_charge($order);
                })->addColumn('tax', function ($order) {
                      return  number_format($order->shipping_charge*.15, 2, ',', ' ');
                 
                })
                ->rawColumns(['shiping_charge', 'cod_charge','tax'])
                ->make(true);
        }


    }

    private function shiping_charge($order)
    {
        if ($order->carrier == 'Aymakan') {
            return $this->AyaMakanCharge($order);
        } elseif ($order->carrier == 'Mkhdoom') {
            return $this->MkhdoomCharge($order);
        } elseif ($order->carrier == 'Tamex') {
            return $this->TamexCharge($order);
        } elseif ($order->carrier == 'Zajil') {
            return $this->ZajilCharge($order);
        } else {
            return 0.00;
        }
    }

    private function AyaMakanCharge($order)
    {
        $price = 23;
        $allwo_weight = 15;
        $extre_weigth_charge = 2; //2 ryal pre kelo
        $Cod_charge = 1.50 / 100;
        $last_price = 0;
        if ($order->weight > $allwo_weight) {
            $extra_weight = $order->weight - $allwo_weight;
            $price_of_extra_weight = $extra_weight * $extre_weigth_charge;
            $last_price = $price + $price_of_extra_weight;
        } else {
            $last_price = $price;
        }
        return number_format($last_price, 2, '.', ' ');
//        if ($order->cod_amount > 0) {
//            $last_prince = $last_price + ($Cod_charge * $order->cod_amount);
//            return number_format($last_price, 2, ',', ' ');
//        }
//        return number_format($last_price, 2, ',', ' ');

    }

    private function MkhdoomCharge($order)
    {
        $price = 18;
        $allwo_weight = 15;
        $extre_weigth_charge = 1.5; //1.5 ryal pre kelo
        $Cod_charge = 0;
        $last_prince = 0;
        if ($order->weight > $allwo_weight) {
            $extra_weight = $order->weight - $allwo_weight;
            $price_of_extra_weight = $extra_weight * $extre_weigth_charge;
            $last_prince = $price + $price_of_extra_weight;
        } else {
            $last_prince = $price;
        }
//        if ($order->cod_amount > 0) {
//            $last_prince = $last_prince + ($Cod_charge * $order->cod_amount);
//            return number_format($last_prince, 2, ',', ' ');
//        }

        return number_format($last_prince, 2, '.', ' ');
    }

    private function TamexCharge($order)
    {

        $allwo_weight = 15;
        $extre_weigth_charge = 2; //2 ryal pre kelo
        $Cod_charge = 0;
        $last_prince = 0;
        $price = $order->city == 'Riyadh' ? 19 : 23;
        if ($order->weight > $allwo_weight) {
            $extra_weight = $order->weight - $allwo_weight;
            $price_of_extra_weight = $extra_weight * $extre_weigth_charge;
            $last_prince = $price + $price_of_extra_weight;
        } else {
            $last_prince = $price;
        }
//        if ($order->cod_amount > 0) {
//            $last_prince = $last_prince + ($Cod_charge * $order->cod_amount);
//            return number_format($last_prince, 2, ',', ' ');
//        }

        return number_format($last_prince, 2, '.', ' ');
    }

    private function ZajilCharge($order)
    {
        $price = 18;
        $allwo_weight = 15;
        $extre_weigth_charge = 2; //2  ryal pre kelo
        $Cod_charge = 2 / 100;
        $last_prince = 0;
        if ($order->weight > $allwo_weight) {
            $extra_weight = $order->weight - $allwo_weight;
            $price_of_extra_weight = $extra_weight * $extre_weigth_charge;
            $last_prince = $price + $price_of_extra_weight;
        } else {
            $last_prince = $price;
        }
//        if ($order->cod_amount > 0) {
//            $last_prince = $last_prince + ($Cod_charge * $order->cod_amount);
//            return number_format($last_prince, 2, ',', ' ');
//        }

        return number_format($last_prince, 2, '.', ' ');
    }


    private function Cod_charge($order, $flag = false)
    {
        if ($order->cod_amount > 0) {
            if ($order->carrier == 'Aymakan') {
                if ($flag == false) {
                    return number_format(1.5 / 100 * $order->cod_amount, 2, '.', ' ');
                }
                return 1.5 / 100 * $order->cod_amount;
            } elseif ($order->carrier == 'Mkhdoom') {
                if ($flag == false) {
                    return number_format(0, 2, '.', ' ');
                }
                return 0;
            } elseif ($order->carrier == 'Tamex') {
                if ($flag == false) {
                    return number_format(0, 2, '.', ' ');
                }
                return 0;
            } elseif ($order->carrier == 'Zajil') {
                if ($flag == false) {
                    return number_format(2 / 100 * $order->cod_amount, 2, '.', ' ');
                }
                return 2 / 100 * $order->cod_amount;
            } else {
                if ($flag == false) {
                    return number_format(0, 2, '.', ' ');
                }
                return 0;
            }
        } else {
            if ($flag == false) {
                return number_format(0, 2, '.', ' ');
            }
            return 0;
        }
    }


    private function total($order)
    {
        $cod = $this->Cod_charge($order, true);
        $shiping_charge = floatval($this->shiping_charge($order));
        return number_format($cod + $shiping_charge, 2, '.', ' ');


    }


    public function ExportExcel(Request $request)
    {

        $order = $this->get_carriers_report($request, true);


        $data = [];
        $i = 0;
        foreach ($order as $or) {
            $data[$i]['shipping_number'] = $or->shipping_number;
            $data[$i]['order_number'] = $or->order_number;
            $data[$i]['carrier'] = $or->carrier;
            $data[$i]['cod_amount'] = $or->cod_amount;
            $data[$i]['tracking_number'] = $or->tracking_number;
            $data[$i]['shiping_charge'] = $this->shiping_charge($or);
            $data[$i]['cod_charge'] = $this->Cod_charge($or);
            $data[$i]['carrier_charge'] = $or->carrier_charge;
            $data[$i]['shipping_date'] = $or->shipping_date;
            $data[$i]['delivery_date'] = $or->delivery_date;

            $i++;
        }

        return Excel::download(new CarrierReportExport($data), 'Carrier.xlsx');
    }


}
