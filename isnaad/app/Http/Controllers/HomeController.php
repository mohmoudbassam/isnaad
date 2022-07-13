<?php

namespace App\Http\Controllers;

use App\carrier;
use App\Classes\Naqel;
use App\Exports\OrderProcessingExport;
use App\city;
use App\city_name;
use App\Classes\Aymakan;
use App\Classes\Mkhdoom;
use App\Classes\Wadha;
use App\Classes\Shipox;
use App\Classes\DOS;
use App\Classes\BARQ;
use App\Classes\FDA;
use App\Classes\MORA;
use App\Classes\Jones;
use App\Classes\LaBaih;
use App\Classes\Kudhha;
use App\Barq_order_id;
use App\Classes\AramexAPI;
use App\Classes\isnaad_wbl;
use App\Classes\Tamex;
use App\Classes\Zajil;
use App\Classes\Sama;
use App\Classes\Smsa;
use App\Classes\Forrun;
use App\Classes\Mahmoul;
use App\Classes\Lastpoint;
use App\Classes\Beez;
use App\Classes\UPS;
use App\Classes\Aja;
use App\Classes\RedBox;
use App\Exports\orderExport;
use App\carrier_city;
use App\Models\descountOrder;
use App\order;
use App\setting;
use App\store;
use Carbon\Carbon;
use Dompdf\Dompdf;
use http\Client\Response;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Yajra\DataTables\DataTables;
use function MongoDB\BSON\toJSON;
use App\Exports\UsersExport;
use App\Imports\orderImport;
use Neodynamic\SDK\Web\WebClientPrint;
use Session;
use App\interrupted_orders;
use App\Helpers\helper;
use App\Helpers\store_shipment;
use App\Helpers\carrier_charge;
use App\constans;
use App\daliay;
use App\user;
use App\Notifications\ExciptionConnection;
use App\Helpers\Carrier_Shipment;
use Illuminate\Support\Facades\Response as REQ;

include_once(app_path() . '/WebClientPrint/WebClientPrint.php');

use App\Models\carrire_shipment;

class HomeController extends Controller
{
    use helper, store_shipment, carrier_charge, Carrier_Shipment;

    static $shipped = 0;
    static $error = 0;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {

        $this->middleware('auth', ['except' => [
            'getOrdersFromShipedge', 'Update_status_tamex', 'Update_status_makhdom'
            , 'Update_status_Aramx', 'Update_status_zajil', 'Update_status_Aymakan',
            'testZajl', 'testUpdateStautsZajl', 'Update_status_Smsa'
            , 'Update_status_Forrun', 'Update_status_mahmoul',
            'update_zajil', 'Update_status_sama', 'Update_status_Wadha', 'Update_status_Shipox', 'Update_status_FDA', 'Update_status_Kudhha'
            , 'update_carrier_charge', 'Update_status_BARQ', 'Cancel_shipment', 'Update_status_LaBaih', 'Update_status_Lastpoint', 'NAqel_Label', 'Update_status_Jones', 'Update_status_MORA', 'update_status_naqel', 'Update_status_UPS'
        ]]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function adminHome()
    {
        return view('adminHome');
    }

    public function smsaawb($tr_no)
    {
        $arguments = array('passKey' => Smsa::$prod_pass_key);
        $arguments['awbNo'] = $tr_no;
        //dd($tr_no);
        $output = Smsa::makeSoapCall('getPDF', $arguments);
        //  dd($output);
        return \Illuminate\Support\Facades\Response::make($output->getPDFResult, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="waybill-' . $arguments['awbNo'] . '.pdf"'
        ]);
    }

    public function test()
    {
        dd(123);
        $order = (object)[
            'orderNum' => '1234',
            'declared_total' => '1155',
            'CODamount' => '1155',
            'custFName' => 'Nezar',
            'custLName' => 'Lulu',
            'custAddress1' => 'test address',
            'custCity' => 'riyadh',
            'custZip' => '12345',
            'custCountry' => 'Saudi Arabia',
            'custPhone' => '9665123456',
            'OrderDate' => '20-4-2020',
            'WeightSum' => '5',
            'Qty_Item' => '2',
        ];
        $shipment = [];
        $shipment = isnaad_wbl::create_shipment($order);
        dd($shipment);
    }

    public function generate_label($tracking_num)
    {

        $label = AramexAPI::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function NAqel_Label($tracking_num)
    {
        //dd($tracking_num);
        $label = Naqel::create_label($tracking_num);
        return \Illuminate\Support\Facades\Response::make($label, 200, [
            'Content-Type' => 'application/pdf',

        ]);


    }

    public function generate_label_Mkhdoom($tracking_num)
    {
        $label = Mkhdoom::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function generate_label_Wadha($tracking_num)
    {
        $label = Wadha::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function generate_label_Shipox($tracking_num)
    {
        $label = Shipox::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function generate_label_FDA($tracking_num)
    {
        $label = FDA::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function generate_label_Kudhha($tracking_num)
    {
        $label = Kudhha::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function generate_label_Lastpoint($tracking_num)
    {
        $label = Lastpoint::create_label($tracking_num);
        return Redirect::to($label);

    }

    public function Cancel_shipment()
    {
        $cancel = Smsa::Cancel_shipment(290121181252);

    }


    public function getOrdersFromShipedge()
    {

        Log::error('cron job begin');
        // $settings= setting::where('name','last_fetched_processing_date')->first();
        //    $processing_date = '2020-01-29 10:00:00';
        //$settings->value;
        //   $settings->value= date('Y-m-d h:i:s');
        // $settings->save();
        //  dd(  DB::connection('shipedge')->getDatabaseName());

        // dd( $orders = \DB::connection('mysql2')->table('shipping_out')->select('*')->get());
        //  $orders = \DB::connection('shipedge')->table('shipping_in')
        // ->where('MLVID', '9106')
        //  ->where('MLVID', 'NOT LIKE', 'rep%')
        //     ->where('AcountID','14')
        //->where('ProcessDate','>',$processing_date)
        //    ->get();
        //dd($orders);

        $processing_order = order::select('shipping_number')->where([['processing_status', '=', '1'], ['active', 1]])->get()->pluck('shipping_number')->toArray();


        $interrubted_count = interrupted_orders::count();

        $constant_shipedge = constans::where('name', 'ship_edig')->first();
        //   $constant_ship_offset = constans::where('name', 'Shipedge_offset')->first();
        $count_procissing = order::where([['processing_status', '=', '1'], ['active', 1]])->count();

        $total = $interrubted_count + $count_procissing;
        //  dd($total);
//dd($total);
//dd($constant_shipedge->value);
        if ($constant_shipedge->value) {
            //   dd('enter');


            $orders1 = \DB::connection('shipedge')->table('shipping_in')->where('MLVID', 'NOT LIKE', 'rep%')->get();
            $count_ship = $orders1->count();


            if ($total >= $count_ship) {
                //   dd(22);
                $interrubted_orders = interrupted_orders::select('shipping_number')->get()->pluck('shipping_number');
                $orders = \DB::connection('shipedge')->table('shipping_in')
                   // ->where('MLVID', '274503')
                   ->whereIn('MLVID', $interrubted_orders)
                    ->where('MLVID', 'NOT LIKE', 'rep%')
                    // ->where('AcountID','!=','58')
                    // ->where('custCity','riyadh')
                    // ->where('AcountID', 'NOT LIKE', '43')
                    //   ->where('AcountID','13')
                    // ->where('ProcessDate','>',$processing_date)

                    ->get();
                //  dd($orders);
                // dd('enter','en try');
                //   dd($orders);
            } else {

                // dd($total);
                $orders = \DB::connection('shipedge')->table('shipping_in')
                    ->whereNotIn('MLVID', $processing_order)
                    //->where('MLVID', '274503')
                    ->where('MLVID', 'NOT LIKE', 'rep%')
                    //->where('AcountID','=','58')
                    // ->where('shipping_carrier','=','Aramex')

                    //      ->where('AcountID','13')
                    //  ->where('AcountID', 'NOT LIKE', '43')
                    // ->where('custCity','=','riyadh')
                    //   //->where('ProcessDate','>',$processing_date)
                    // ->offset(500)
                    //->limit(100)
                    ->get();

                //     dd('in else');
            }
            //  dd(123);


        }
        //dd($orders);

        foreach ($orders as $order) {
            // dd('not enter');
            $app_order =
                order::where('shipping_number', $order->MLVID)
                    ->where('active', 1)->first();

            if ($app_order) {
                // dd(123);
                continue;
            }
            /*
            $descountOrder = descountOrder::where([['order_number', $order->orderNum]])->first();
            // dd($descountOrder);
            if ($descountOrder != null) {

                try {
                    $first = $order->CODamount * .15;
                } catch (\Exception $e) {
                    dd("cod" . $order->CODamount . "orn" . $order->MLVID);
                }


                $first = $order->CODamount - $first;
                $seconde = $first * .1;

                $seconde = $first - $seconde;
                $third = $seconde * .15;
                $third = $seconde + $third;
                $order->CODamount = $third;

            }
            */
            // dd(123);
            $haserror = 0;
            $store = store::where('account_id', $order->AcountID)->first();
            //$store_id = $store->id;
            if (!$store) {
                // dd(123);
                Log::error('Store id does not exist for order ' . $order->MLVID);
                continue;
            }
            $ordernumber = str_replace('R-', '', $order->orderNum);
            //  dd($ordernumber);
            $checkordernum =
                order::where('order_number', $ordernumber)
                    ->where('active', 1)
                    ->where('store_id', $order->AcountID)->first();
            if ($checkordernum) {
                $interrupted_orders_table =
                    interrupted_orders::where('shipping_number', $order->MLVID)
                        ->first();
                if (!$interrupted_orders_table) {
                    // dd(123);
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = $store->name;
                    $interrubted_orders->issue = 'order already exist';
                    $interrubted_orders->country = $order->custCountry;
                    $interrubted_orders->save();
                    Log::error('order already exist ' . $order->MLVID);
                    continue;
                }
                $haserror = 1;
                continue;
            }

            $carrier = $order->shipping_carrier;


            $order->sender_name = $store->name;
            $order->contact_name = $store->contact_person;
            $order->sender_email = $store->email;
            $order->sender_phone = $store->phone;
            $order->CODamount = ($order->CODamount) ? $order->CODamount : 0;
            $order->custCity = trim($order->custCity);
            $order->custCity = str_replace('\\', '', $order->custCity);
            $city = city::where('name', $order->custCity)->first();
            if ($city) {
                $order->custCity = $city->name;
                $city_id = $city->id;
            } else {
                $city_name = city_name::where('name', $order->custCity)->first();
                if ($city_name) {
                    $city = city::where('id', $city_name->city_id)->first();
                    $order->custCity = $city->name;
                    $city_id = $city->id;
                    //dd($city->name);
                } else {
                    $interrupted_orders_table =
                        interrupted_orders::where('shipping_number', $order->MLVID)
                            ->first();
                    if (!$interrupted_orders_table) {
                        // dd(123);
                        $interrubted_orders = new interrupted_orders();
                        $interrubted_orders->shipping_number = $order->MLVID;
                        $interrubted_orders->order_number = $order->orderNum;
                        $interrubted_orders->carrier = $order->shipping_carrier;
                        $interrubted_orders->store = $store->name;
                        $interrubted_orders->issue = 'Address';
                        $interrubted_orders->country = $order->custCountry;
                        $interrubted_orders->save();
                        Log::error('City name is incorrect for order ' . $order->MLVID);
                        continue;
                    }
                    $haserror = 1;
                    continue;
                    //dd(123);
                }
            }


            if ($order->custCountry == 'SA') {

                $check_phone = $this->validate_phone($order->custPhone, 'SA');
                if ($check_phone == false) {
                    $interrupted_orders_table =
                        interrupted_orders::where('shipping_number', $order->MLVID)
                            ->first();
                    if (!$interrupted_orders_table) {
                        $interrubted_orders = new interrupted_orders();
                        $interrubted_orders->shipping_number = $order->MLVID;
                        $interrubted_orders->order_number = $order->orderNum;
                        $interrubted_orders->carrier = $order->shipping_carrier;
                        $interrubted_orders->store = $store->name;
                        $interrubted_orders->issue = 'Phone';
                        $interrubted_orders->country = $order->custCountry;
                        $interrubted_orders->save();
                        Log::error('Phone is incorrect for order ' . $order->MLVID);
                        continue;
                    }
                    $haserror = 1;
                    continue;
                }

            }
            if ($order->custCountry != 'SA' && $carrier == 'AUTO-SHIP') {
                $interrupted_orders_table =
                    interrupted_orders::where('shipping_number', $order->MLVID)
                        ->first();
                if (!$interrupted_orders_table) {
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = $store->name;
                    $interrubted_orders->issue = 'International Order';
                    $interrubted_orders->country = $order->custCountry;
                    $interrubted_orders->save();
                    Log::error('International Order ' . $order->MLVID);
                    continue;
                }
                $interrupted_orders_table->issue = 'International Order';
                $interrupted_orders_table->save();
                Log::error('International Order ' . $order->MLVID);
                $haserror = 1;
                continue;


            }
            if ($haserror == 0) {
                $interrupted_orders_table =
                    interrupted_orders::where('shipping_number', $order->MLVID)
                        ->delete();
            }

            /*     if($carrier != 'PICK' && $order->AcountID == 14){
                                   $interrupted_orders_table =
                                  interrupted_orders::where('shipping_number', $order->MLVID)
                                      ->first();
                              if (!$interrupted_orders_table) {
                                  $interrubted_orders = new interrupted_orders();
                                  $interrubted_orders->shipping_number = $order->MLVID;
                                  $interrubted_orders->order_number = $order->orderNum;
                                  $interrubted_orders->carrier = $order->shipping_carrier;
                                  $interrubted_orders->store = $store->name;
                                  $interrubted_orders->issue = 'wadimanuka order';
                                  $interrubted_orders->save();
                                  Log::error('wadimanuka order' . $order->MLVID);
                                  continue;
                                 }
                          }*/

            //  dd($order->MLVID);
            $shipment = [];
            $is_auto_ship = 0;
            if ($carrier == "Aramex") {
                if ($order->custCountry != 'SA') {
                    $interrupted_orders_table =
                        interrupted_orders::where('shipping_number', $order->MLVID)
                            ->first();
                    if (!$interrupted_orders_table) {
                        $interrubted_orders = new interrupted_orders();
                        $interrubted_orders->shipping_number = $order->MLVID;
                        $interrubted_orders->order_number = $order->orderNum;
                        $interrubted_orders->carrier = $order->shipping_carrier;
                        $interrubted_orders->store = $store->name;
                        $interrubted_orders->issue = 'International Order';
                        $interrubted_orders->country = $order->custCountry;
                        $interrubted_orders->save();
                        Log::error('International Order ' . $order->MLVID);
                        continue;
                    }
                    $interrupted_orders_table->issue = 'International Order';
                    $interrupted_orders_table->save();
                    Log::error('International Order ' . $order->MLVID);
                    $haserror = 1;
                    continue;
                } else {
                    $shipment = AramexAPI::create_shipment($order);
                    //dd($shipment);
                    $carrier = 'Aramex';
                    $ship_method = "Aramex";
                }
            } elseif ($carrier == "Mkhdoom") {
                $shipment = Mkhdoom::create_shipment($order);
                $carrier = 'Mkhdoom';
                $ship_method = "Mkhdoom";
            } elseif ($carrier == "Tamex") {
                $shipment = Tamex::create_shipment($order);
                $carrier = 'Tamex';
                $ship_method = "Tamex";
            } elseif ($carrier == "Aymakan") {
                $shipment = Aymakan::create_shipment($order);
                $carrier = 'Aymakan';
                $ship_method = "Aymakan";
            } elseif ($carrier == "Zajil") {
                $shipment = RedBox::create_shipment($order);
                $carrier = 'RedBox';
                $ship_method = "RedBox";

            } elseif ($carrier == "SMSA") {
                $shipment = Smsa::create_shipment($order);
                $carrier = 'Smsa';
                $ship_method = "Smsa";
            } elseif ($carrier == "PICK") {
                //  $shipment = Lastpoint::create_shipment($order);
                //  $carrier = 'Lastpoint';
                //$ship_method = "Lastpoint";
                $shipment = isnaad_wbl::create_shipment($order);
                $carrier = 'Pick';
                $ship_method = "Pick";
            } elseif ($carrier == "Forrun") {
                $shipment = Forrun::create_shipment($order);
                $carrier = 'Forrun';
                $ship_method = "Forrun";
            } elseif ($carrier == "SAMA") {
                $shipment = SAMA::create_shipment($order);
                $carrier = 'SAMA';
                $ship_method = "SAMA";
            } elseif ($carrier == "Mahmoul") {
                $shipment = Mahmoul::create_shipment($order);
                $carrier = 'Mahmoul';
                $ship_method = "Mahmoul";
            } elseif ($carrier == "Wadha") {
                //  dd(1239);
                $shipment = Wadha::create_shipment($order);
                $carrier = 'Wadha';
                $ship_method = "Wadha";
            } elseif ($carrier == "DOS") {
                //  dd(1239);
                $shipment = DOS::create_shipment($order);
                $carrier = 'DOS';
                $ship_method = "DOS";
            } elseif ($carrier == "Shipox") {
                //  dd(1239);
                $shipment = Shipox::create_shipment($order);
                $carrier = 'Shipox';
                $ship_method = "Shipox";
            } elseif ($carrier == "BARQ") {
                //dd(1239);
                $shipment = BARQ::create_shipment($order);
                $carrier = 'BARQ';
                $ship_method = "BARQ";
            } elseif ($carrier == "FDA") {
                //  $shipment = LaBaih::create_shipment($order);
                //  $carrier = 'LaBaih';
                // $ship_method = "LaBaih";
                $shipment = FDA::create_shipment($order);
                $carrier = 'FDA';
                $ship_method = "FDA";
            } elseif ($carrier == "LaBaih") {
                $shipment = LaBaih::create_shipment($order);
                $carrier = 'LaBaih';
                $ship_method = "LaBaih";
            } elseif ($carrier == "Lastpoint") {
                $shipment = Lastpoint::create_shipment($order);
                $carrier = 'Lastpoint';
                $ship_method = "Lastpoint";
            } elseif ($carrier == "Naqel") {
                $shipment = Naqel::create_shipment($order);
                $carrier = 'Naqel';
                $ship_method = "Naqel";

            } elseif ($carrier == "Jones") {
                $shipment = Jones::create_shipment($order);
                $carrier = 'Jones';
                $ship_method = "Jones";

            } elseif ($carrier == "MORA") {
                $shipment = MORA::create_shipment($order);
                $carrier = 'MORA';
                $ship_method = "MORA";

            } elseif ($carrier == "Kudhha") {
                $shipment = Kudhha::create_shipment($order);
                $carrier = 'Kudhha';
                $ship_method = "Kudhha";

            } elseif ($carrier == "UPS") {
                $shipment = UPS::create_shipment($order);
                $carrier = 'UPS';
                $ship_method = "UPS";
            } elseif ($carrier == 'AUTO-SHIP') {

                /*  if ($order->custCity == 'Buraydah' && $order->AcountID == '13') {
                      // dd('ss');
                      $shipment = Shipox::create_shipment($order);
                      $carrier = 'Shipox';
                      $ship_method = "Shipox";
                 } else*/
                if ($order->custCountry == 'SA' || $order->custCountry = 'KSA') {
                    // dd('kkkk');
                    $order->custCountry = 'SA';
                    if (strtolower($order->custCity) == 'riyadh') {

                        /*
                                                $mytime = Carbon::now();
                                                $thisDay = Carbon::parse($mytime)->startOfDay();
                                                $thisDay = $thisDay->toDateTimeString();
                                                $orders = order::where([['carrier', 'Kudhha'],['city', 'riyadh'], ['created_at', '>=', $thisDay]])->get();

                                                if ($orders->count() <= 9 ) {
                                                    $shipment = Kudhha::create_shipment($order);
                                                    $carrier = 'Kudhha';
                                                    $ship_method = "Kudhha";

                                                }*/

                        if ($order->AcountID == 54) {

                            $shipment = Mahmoul::create_shipment($order);
                            $carrier = 'Mahmoul';
                            $ship_method = 'Mahmoul';

                        } elseif ($order->CODamount == 0) {

                            $shipment = Kudhha::create_shipment($order);
                            $carrier = 'Kudhha';
                            $ship_method = "Kudhha";
                        } else {
                            $is_auto_ship = 1;
                            $auto_ship_result = $this->shipment_in($order);
                            // dd($auto_ship_result);
                            if ($auto_ship_result == false) {
                                $this->store_inurruptued($order, $store, 'Naqel city issue');
                                continue;
                            }
                        }
                    } else {


                        if ($order->AcountID == 54) {
                            $carrier_city = carrier_city::where([['carrier_id', '8'], ['city_id', $city_id]])->first();
                            if ($carrier_city) {
                                $shipment = Mahmoul::create_shipment($order);
                                $carrier = 'Mahmoul';
                                $ship_method = 'Mahmoul';
                            } else {
                                $is_auto_ship = 1;
                                $auto_ship_result = $this->shipment_out($order, $city_id);
                            }
                        } elseif ($order->AcountID == 58) {
                            $carrier_cityups = carrier_city::where([['carrier_id', '23'], ['city_id', $city_id]])->first();
                            $carrier_cityTamex = carrier_city::where([['carrier_id', '3'], ['city_id', $city_id]])->first();

                            if ($carrier_cityups) {

                                $shipment = UPS::create_shipment($order);
                                $carrier = 'UPS';
                                $ship_method = 'UPS';

                            } elseif ($carrier_cityTamex) {
                                $shipment = Tamex::create_shipment($order);
                                $carrier = 'Tamex';
                                $ship_method = 'Tamex';
                            } else {

                                $shipment = AramexAPI::create_shipment($order);
                                $carrier = 'Aramex';
                                $ship_method = 'EAMXDOM';

                            }
                        } else {
                            $is_auto_ship = 1;
                            $auto_ship_result = $this->shipment_out($order, $city_id);

                        }

                    }
                } else {
                    $shipment = AramexAPI::create_shipment($order);
                    $carrier = 'Aramex';
                    $ship_method = 'EAMXEPE';
                }
            } else {
                Log::error($carrier . ' is not supported for order ' . $order->MLVID);
                continue;
            }
            //  dd(  $shipment);
            if ($is_auto_ship == 1) {
                try {
                    //  dd( $shipment);
                    $shipment = $auto_ship_result['shipment'];
                } catch (\Throwable $exception) {
                    // dd($order,$exception);
                }
                // dd(1234);

            }

            //$shipment = json_encode($shipment);

            if ($shipment != 0) {

                foreach ($shipment as $key => $val) {
                    if ($key == 'status') {
                        $status = $shipment['status'];

                    };
                }
            } else {
                $interrupted_orders_table =
                    interrupted_orders::where('shipping_number', $order->MLVID)
                        ->first();
                if (!$interrupted_orders_table) {
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = $store->name;
                    $interrubted_orders->issue = 'carrier issue';
                    $interrubted_orders->country = $order->custCountry;
                    $interrubted_orders->save();
                    Log::error('carrier_issue' . $order->MLVID);
                    Log::error($carrier . ' error in order ' . $order->MLVID);
                }
                // dd(123);
                continue;
            }

            // dd($order->MLVID);
            // dd($status);
            if (isset($status)) {
                if ($status == 'success') {

                    //   if ($order->shipping_carrier == 'PICK') {
                    //      $shipping_charge = 5;
                    //  }else
                    // {

                    if ($order->custCountry != 'SA') {
                        if ($order->WeightSum > $store->weight_out_sa) {
                            $diff = $order->WeightSum - $store->weight_out_sa;
                            $dif_tot = $diff / $store->weight_out_sa;
                            $total = $dif_tot * $store->add_cost_out_sa;
                            $shipping_charge = $store->shipping_charge_international + $total;
                        } else {
                            $shipping_charge = $store->shipping_charge_international;
                            //dd($shipping_charge);
                        }
                    } else {
                        if ($order->custCity == 'Riyadh') {
                            if ($order->WeightSum > $store->weight_in_sa) {
                                $diff = $order->WeightSum - $store->weight_in_sa;
                                $total = $diff * $store->add_cost_in_sa;
                                $shipping_charge = $store->shipping_charge_in_ra + $total;
                            } else {
                                $shipping_charge = $store->shipping_charge_in_ra;
                                // dd($shipping_charge);
                            }
                        } else {
                            if ($order->WeightSum > $store->weight_in_sa) {
                                $diff = $order->WeightSum - $store->weight_in_sa;
                                $total = $diff * $store->add_cost_in_sa;
                                $shipping_charge = $store->shipping_charge_out_ra + $total;
                            } else {
                                $shipping_charge = $store->shipping_charge_out_ra;
                                // dd($shipping_charge);
                            }
                        }
                    }
                    // }
                    $carrier_name = $is_auto_ship == 0 ? $carrier : $auto_ship_result['carrier'];

                    if ($carrier_name == 'Smsa' || $carrier_name == 'Shipox' || $carrier_name == 'LaBaih') {
                        $carrier_charge = 0;
                    } else {
                        //  $carrier_charge=  $this->carrier_charge($carrier_name,$order);
                    }

                    $order_data = array(
                        'carrier' => $is_auto_ship == 0 ? $carrier : $auto_ship_result['carrier'],
                        'ship_method' => $is_auto_ship == 0 ? $ship_method : $auto_ship_result['shiped_methode'],
                        'tracking_number' => $is_auto_ship == 0 ? $shipment['tracking_number'] : $auto_ship_result['shipment']['tracking_number'],
                        'cod_amount' => $order->CODamount,
                        'awb_url' => $is_auto_ship == 0 ? $shipment['waybill_url'] : $auto_ship_result['shipment']['waybill_url'],
                        'store_id' => $order->AcountID,
                        'shipping_number' => $order->MLVID,
                        'order_number' => $order->orderNum,
                        // 'shipping_charge' =>
                        'shipping_charge' => $shipping_charge,
                        'cod_charge' => ($order->CODamount > 0) ? $store->cod_charge : 0,
                        'processing_status' => 1,
                        'delivery_status' => 0,
                        'processing_date' => $store->processing_date,
                        'weight' => $order->WeightSum,
                        'description' => $order->All_Sku,
                        'Qty_Item' => $order->Qty_Item,
                        'fname' => $order->custFName,
                        'lname' => $order->custLName,
                        'country' => $order->custCountry,
                        'city' => $order->custCity,
                        'state' => $order->custState,
                        'zip_code' => $order->custZip,
                        'phone' => $order->custPhone,
                        'address_1' => $order->custAddress1,
                        'address_2' => $order->custAddress2,
                        'ProcessDate' => $order->ProcessDate,
                        'Height' => $order->Height,
                        'carrier_charge' => 0

                    );
                    order::create($order_data);

                } else {
                    $interrubted_orders = new interrupted_orders();
                    $interrubted_orders->shipping_number = $order->MLVID;
                    $interrubted_orders->order_number = $order->orderNum;
                    $interrubted_orders->carrier = $order->shipping_carrier;
                    $interrubted_orders->store = $store->name;
                    $interrubted_orders->issue = 'Issue From Carrier';
                    $interrubted_orders->save();
                    Log::error('Order not added ' . $order->MLVID);
                    continue;
                }
            } else {
                $interrubted_orders = new interrupted_orders();
                $interrubted_orders->shipping_number = $order->MLVID;
                $interrubted_orders->order_number = $order->orderNum;
                $interrubted_orders->carrier = $order->shipping_carrier;
                $interrubted_orders->store = $store->name;
                $interrubted_orders->issue = 'Issue From Carrier';
                $interrubted_orders->save();
                Log::error('issue in status' . $order->MLVID);
                continue;
            }
        }

        //   return \redirect('/orders');
        //  $interrubted_count = interrupted_orders::count();
        // $constant_ship_offset->value = $constant_ship_offset->value + 100;
        //  $constant_ship_offset->save();
        //   $total= $interrubted_count+$constant_ship_offset->value;
        //   if ($total >= $count_ship) {
        //        $constant_ship_offset->value = 0;
        //      $constant_ship_offset->save();
        //  }

    }


    public static function create_label()
    {
        Mkhdoom::create_label('D4373DBAE76A4795', '9968666796830');

    }

    public function importExportView()
    {
        return view('newDesign.mainPage.bulk_ship');
    }

    public function import(Request $request)
    {

        $security_key = constans::where('name', 'security_key')->get();

        if ($security_key[0]->value != $request->security_key) {
            return \redirect()->back()->withErrors(['security key not valid']);
        }
        try {
            $shipped = 0;
            $error = 0;
            $data = Excel::toArray(new orderImport(), request()->file('file'));
            collect(head($data))
                ->each(function ($row, $key) {
                    $order =
                        order::where('tracking_number', $row['tracking_number'])
                            ->where('carrier', $row['carrier'])
                            // ->where('processing_status', 1)
                            ->first();
                    if ($order) {
                        self::$shipped++;
                        $order->update(['shipping_date' => $row['shipping_date'], 'processing_status' => 0]);
                        $dataArray = [
                            'MLVID' => $order->shipping_number,
                            'orderID' => $order->shipping_number,
                            'AcountID' => $order->store_id,
                            'statusOut' => 'shipped',
                            'TrackingNumber' => $order->tracking_number,
                            'Carrier' => $order->carrier,
                            'Ship_Method' => $order->ship_method,
                            'FinalPostage' => $order->shipping_charge + $order->cod_charge,
                            'TypeShipping' => 'Isnaad_App',
                            'DateOut' => date("Y-m-d h:i:s", time())
                        ];

                        //   DB::connection('shipedge')->enableQueryLog();
                        DB::connection('shipedge')->table('shipping_out')->insert($dataArray);
                        //      $queries = DB::getQueryLog();
                        //  dd($queries);
                    } else {
                        self::$error++;
                        Log::error('Order not found ' . $row['tracking_number']);
                    }
                });
            $file = $request->file('file');

            $time = microtime('.') * 10000;
            $filename = $time . '.' . strtolower($file->getClientOriginalExtension());
            $real_name = $file->getClientOriginalName();
            $destination = 'Daliay';

            $real_name = pathinfo($real_name, PATHINFO_FILENAME);
            $file->move($destination, $filename);

            daliay::create([
                'real_name' => $real_name,
                'storage_name' => $filename,
                'user_id' => auth()->user()->id
            ]);

            return back()->with('success', self::$shipped . ' Orders imorted' . ' - ' . self::$error . ' not imported');
            //return \redirect('bulk_ship');
        } catch (\Exception $exception) {
            Log::error('error found ' . $exception->getMessage());
            return 0;
        }
    }

    public function Update_status_tamex()
    {
        $constant = constans::where('name', 'tamex_offset')->first();
        $orders1 = order::where([['carrier', 'Tamex'], ['active', '1'], ['order_status', 'inTransit']])->get();
        $count = $orders1->count();

        // $orders = order::where([['carrier', 'Tamex'], ['active', '1'], ['order_status', 'inTransit']])->offset($constant->value)->limit(50)->get();
//$orders = order::where(['tracking_number', '12098730854438'])->get();


        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        //foreach ($orders as $order) {
        // $tracking_num = $order->tracking_number;
        $id = Tamex::update_status(12098730854438, 114912);
        //}

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_makhdom()
    {

        $constant = constans::where('name', 'mkhdoom_offset')->first();
        $orders1 = order::where([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Mkhdoom'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(50)->get();


        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = Mkhdoom::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }

    }

    public function Update_status_Wadha()
    {
        $constant = constans::where('name', 'wadha_offset')->first();
        $orders1 = order::where([['carrier', 'Wadha'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Wadha'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'Wadha'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Wadha'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(50)->get();

        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = Wadha::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_Shipox()
    {
        $orders = order::where([['carrier', '=', 'Shipox'], ['order_status', '=', 'inTransit'], ['active', '1'], ['processing_status', '0']])->get();
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            Shipox::update_status($tracking_num, $order->id);
        }
    }

    public function Update_status_FDA()
    {
        $constant = constans::where('name', 'FDA_offset')->first();
        $orders1 = order::where([['carrier', 'FDA'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'FDA'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'FDA'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'FDA'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(50)->get();
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = FDA::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_Lastpoint()
    {
        $constant = constans::where('name', 'Lastpoint_offset')->first();
        $orders1 = order::where([['carrier', 'Lastpoint'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Lastpoint'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'Lastpoint'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Lastpoint'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(50)->get();

        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = Lastpoint::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_Aramx()
    {
        // AramexAPI::update_status('32520386850','136051');

        Log::error('aramex cron job started');

        $constant = constans::where('name', 'aramex_offset')->first();
        $orders1 = order::where([['carrier', 'Aramex'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Aramex'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'Aramex'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Aramex'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(100)->get();
        // $count2= $orders->count();
        // dd( $count2);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = AramexAPI::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 100;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_zajil()
    {
        //  dd('sdf');
        $orders = order::where([['order_status', '=', 'Delivered'], ['carrier', '=', 'Zajil'], ['active', 1]])->limit(290)->get();

//dd( $orders->count());
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            Zajil::update_status($tracking_num, $order->id);
        }
    }

    public function Update_status_Aymakan()
    {
        $constant = constans::where('name', 'aymakan_offset')->first();
        $orders1 = order::where([['carrier', 'Aymakan'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Aymakan'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'Aymakan'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Aymakan'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(50)->get();


        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = Aymakan::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_Smsa()
    {

        $constant = constans::where('name', 'smsa_offset')->first();
        $orders1 = order::where([['carrier', 'Smsa'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Smsa'], ['active', '1'], ['order_status', 'Data Uplouded']])->get();
        $count = $orders1->count();

        $orders = order::where([['carrier', 'Smsa'], ['active', '1'], ['order_status', 'inTransit']])->orWhere([['carrier', 'Smsa'], ['active', '1'], ['order_status', 'Data Uplouded']])->offset($constant->value)->limit(50)->get();


        // $orders = order::where([['id','120378']])->get();
        //  dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            $id = Smsa::update_status($tracking_num, $order->id);
        }

        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
        }
    }

    public function Update_status_sama()
    {
        $orders = order::query()->where([['carrier', '=', 'SAMA'], ['active', '1'], ['order_status', '!=', 'Delivered'], ['order_status', '!=', 'Returned']])->get();
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            \App\Classes\Sama::update_status($order->tracking_number, $order->id);
        }

    }

    public function Update_status_DOS()
    {

        Log::error('cron job DOS');

        $orders = order::where([['carrier', '=', 'DOS'], ['order_status', '!=', 'Delivered']])->get();

        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            DOS::update_status($tracking_num, $order->id);
        }
    }

    public function Update_status_BARQ()
    {
        $orders = order::where([['carrier', '=', 'BARQ'], ['order_status', '!=', 'Delivered'], ['active', '1']])->get();
        foreach ($orders as $order) {
            $barq_id = Barq_order_id::where([['ship_no', $order->shipping_number]])->first();
            $barq_order_id = $barq_id->barq_id;
            Barq::update_status($order->shipping_number, $barq_order_id);
        }
    }

    public function Update_status_UPS()
    {
        $orders = order::where([['carrier', '=', 'UPS'], ['order_status', '=', 'inTransit'], ['active', '1']])
            ->orWhere([['carrier', '=', 'UPS'], ['order_status', '=', 'Data Uplouded'], ['active', '1']])
            //->limit(1)
            ->get();
        //dd($orders);
        foreach ($orders as $order) {
            UPS::update_status($order->tracking_number, $order->id);
        }
    }

    public function Update_status_LaBaih()
    {

        Log::error('cron job LaBaih');

        $orders = order::where([['carrier', '=', 'LaBaih'], ['order_status', '=', 'inTransit']])->get();
        // $orders = order::where([['carrier', 'LaBaih'], ['active', '1'], ['order_status', 'Delivered'], ['processing_status', '0']])->offset(1000)->limit(100)->whereBetween('created_at',['2020-04-01','2021-03-01'])->get();
//dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            //  dd($tracking_num);
            // LaBaih::update_status('21041312024213', '231834');
            LaBaih::update_status($tracking_num, $order->id);
        }
    }

    public function Update_status_Jones()
    {

        Log::error('cron job Jones');

        $orders = order::where([['carrier', '=', 'Jones'], ['order_status', '=', 'inTransit']])->get();
        //dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            Jones::update_status($tracking_num, $order->id);
        }
    }

    public function Update_status_MORA()
    {

        Log::error('cron job MORA');

        $orders = order::where([['carrier', '=', 'MORA'], ['order_status', '=', 'inTransit']])->get();

        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            MORA::update_status($tracking_num, $order->id);
        }
    }

    public function Update_status_naqel()
    {

        Log::error('cron job naqel');

        $orders = order::where([['carrier', '=', 'naqel'], ['order_status', '=', 'inTransit'], ['active', '=', '1']])->get();

        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            naqel::update_status($tracking_num, $order->id);
        }
    }

    public function newDesign()
    {
        $stores = store::all();
        $carreires = carrier::all();

        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());
        //dd($wcpScript);
        $array = [
            'stores' => $stores,
            'carriers' => $carreires, 'wcpScript' => $wcpScript
        ];
        return view('m_design.Reports.isnaad_report', $array);
    }

    public function get_processing_order(Request $request, $flag = false)
    {
        $orders = order::query()->with(['store', 'order_printed']);
        $orders = $orders->where('active', '=', '1');
        if (auth()->user()->id == 100) {
            $orders = $orders->where('store_id', 2);
        }
        if ($request->has('carierrs') && $request->get('carierrs') != '') {

            $orders = $orders->where('carrier', $request->carierrs);
        }
        if ($request->has('from') && $request->get('from') != '') {

            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
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

            $orders = $orders->whereHas('store', function ($query) use ($request) {
                $query->where('account_id', '=', $request->get('store'));
            });
        }

        if ($request->has('printed') && $request->get('printed') != '') {
            //dd('sfd');
            if ($request->get('printed') == 0) {
                $orders = $orders->whereHas('order_printed', function ($query) use ($request) {
                    $query->where('count', '>', 0);
                });
            } else {
                $orders = $orders->WhereDoesntHave('order_printed');
            }

        }

        $orders->where('processing_status', '=', '1');
        if ($flag) {
            return $orders;

        } else {

            return Datatables::of($orders)
                ->addColumn('enable', function ($orders) {
                    return '
                         <span style="width: 20px;">
                         <label class="checkbox checkbox-single">
                         <input type="checkbox" class="select" value="' . $orders->id . '" name="select[]">&nbsp;<span></span></label>
                         </span>

                   ';
                })
                ->rawColumns(['enable'])
                ->make(true);
        }
    }

    public function getProc()
    {
        $stores = store::all();
        $carreires = carrier::all();
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());

        $array = [
            'stores' => $stores,
            'carriers' => $carreires,
            'wcpScript' => $wcpScript
        ];

        return view('m_design.pr', $array);
    }

    public function ExportProcessing(Request $request)
    {
        $order = $this->get_processing_order($request, true);
        $order = $order->with('order_printed')->get();

        $data = [];
        $i = 0;
        foreach ($order as $or) {

            $data[$i]['shipping_number'] = $or->shipping_number;
            $data[$i]['order_number'] = $or->order_number;
            $data[$i]['carrier'] = $or->carrier;
            $data[$i]['cod_amount'] = $or->cod_amount;
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
            if (isset($or->order_printed)) {
                $data[$i]['printed'] = $or->order_printed->count;
            } else {
                $data[$i]['printed'] = 0;
            }
            $data[$i]['created_at'] = $or->created_at;
            $i++;
        }

        return Excel::download(new OrderProcessingExport($data), 'orders.xlsx');
    }

    public function testZajl()
    {
        Zajil::update_status('Z089964', '392');
        $orders = order::where([['order_status', '!=', 'Delivered'], ['carrier', '=', 'zajil']])->get();
        //   foreach ($orders as $order) {
        //   Zajil::update_status($tracking_num,$order->id);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, url('testUpdateStautsZajl/' . '89124' . '/' . 'Z1092295'));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",

        ));

        try {
            $result = curl_exec($curl);
            $err_in = curl_error($curl);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        curl_close($curl);


        //    }

    }

    public function testUpdateStautsZajl(Request $request)
    {
        $tracking_number = $request->tracking_number;
        $id = $request->id;

        $result = Zajil::send_request([], 'track?reference_number=' . $tracking_number, 'GET');

        if (isset($result->status)) {

            if ($result->status == 'delivered') {

                $re = order::where('id', $id)->update(['order_status' => 'Delivered']);

            } elseif ($result->status == 'rto') {

                $re = order::where('id', $id)->update(['order_status' => 'Returned']);

            } else {

                $re = order::where('id', $id)->update(['order_status' => 'inTransit']);

            }
        } else {

        }
    }

    public function Update_status_Forrun()
    {

        Log::error('Forrun cron job started');

        $constant = constans::where('name', 'Forrun_offset')->first();
        // $orders = order::where([['order_status', '!=', 'Delivered'], ['carrier', '=', 'Forrun'], ['active', '1']])->get();
        $orders1 = order::where([['order_status', '=', 'Delivered'], ['carrier', '=', 'Forrun'], ['active', '1']])->get();
        $count = $orders1->count();
        $orders = order::where([['order_status', '=', 'Delivered'], ['carrier', '=', 'Forrun'], ['active', '1']])->offset($constant->value)->limit(50)->get();
//dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            Forrun::update_status($tracking_num, $order->id);
        }
        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value == $count) {
            Log::error('Forrun all done');
        }

    }

    public function Update_status_mahmoul()
    {
        Log::error('mahmoul cron job started');

        $constant = constans::where('name', 'mahmoul_offset')->first();
        // $orders = order::where([['order_status', '!=', 'Delivered'], ['carrier', '=', 'Mahmoul'], ['active', '1']])->get();
        $orders1 = order::where([['order_status', '=', 'inTransit'], ['carrier', '=', 'Mahmoul'], ['active', '1']])->get();
        $count = $orders1->count();
        $orders = order::where([['order_status', '=', 'inTransit'], ['carrier', '=', 'Mahmoul'], ['active', '1']])->offset($constant->value)->limit(50)->get();
//dd($orders);
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            Mahmoul::update_status($tracking_num, $order->id);
        }
        $constant->value = $constant->value + 50;
        $constant->save();
        if ($constant->value >= $count) {
            $constant->value = 0;
            $constant->save();
            Log::error('mahmoul all done');
        }
    }

    public function update_zajil()
    {
        $orders = order::doesntHave('orderZajil')->where([['order_status', '!=', 'Delivered'], ['order_status', '!=', 'Returned'], ['carrier', '=', 'Zajil'], ['active', 1]])->get();
        foreach ($orders as $order) {
            $tracking_num = $order->tracking_number;
            Zajil::update_status($tracking_num, $order->id);
        }
    }

    //  private function carrier_charge($carrier, $order)
    //   {
    // dd($carrier, $order);
    //  $carrier_charge =  $this->carrier_charge($carrier, $order);
    //return $carrier_charge;
    //}

    public function update_carrier_charge()
    {
        //dd('mahmoud');
        $ors = order::where([['active', 1], ['carrier', 'Lastpoint']])->whereBetween('created_at', ['2021-04-01', '2021-04-31'])->offset(400)->limit(200)->get();
        $count = $ors->count();
//dd($count);

        $ors = $ors->map(function ($order) {
            return collect([(object)[
                'custCountry' => $order->country,
                'custCity' => $order->city,
                'carrier' => $order->carrier,
                'WeightSum' => $order->weight,
                'shipping_number' => $order->shipping_number,
                'CODamount' => $order->cod_amount,
                'order_status' => $order->order_status
            ]

            ]);

        });

        foreach ($ors as $or) {

            $order = $or[0];
            // $carrier_charge=$this->carrier_charge('Tamex', $order);
            // dd($carrier_charge);
            // $order->carrier_charge= $carrier_charge;
            //    dd($order);
            if ($order->custCity == 'Riyadh') {
                order::where([['active', 1], ['shipping_number', $order->shipping_number]])->update(
                    [
                        'carrier_charge' => 18
                    ]);
            } else {
                order::where([['active', 1], ['shipping_number', $order->shipping_number]])->update(
                    [
                        'carrier_charge' => 19
                    ]);
            }

        }
    }

    public function aya($order)
    {

        if ($order->CODamount > 0 && $order->order_status == 'Delivered') {
            $carrier_charge = $order->carrier_charge + (.01 * $order->CODamount);

        } elseif ($order->order_status == 'Returned') {
            $carrier_charge = 10;

        }
        return $order->carrier_charge;
    }


    public function get_smsa_charge()
    {

        $orders = order::where([['carrier', 'Smsa'], ['active', '=', '1']])->whereBetween('shipping_date', ['2021-12-01', '2021-12-31'])->get();
        $count = $orders->count();
        //dd($count);
        if ($count < 5000) {
            $this->smsmLess500($orders);
        }
    }

    public function smsmLess500($orders)
    {
        // dd($orders);
        foreach ($orders as $order) {
            $is_return = $order->order_status == 'Returned' ? 1 : 0;
            if ($order->city == 'riyadh' || $order->city == 'Dammam' || $order->city == 'Jeddah') {
                $price = 20;

                if ($order->weight > 15) {
                    $extra_weight = $order->weight - 15;
                    $extra_price = $extra_weight * 1;
                    $price = $price + $extra_price;

                    if ($order->cod_amount > 0 && $is_return == 0) {

                        $price = $price + $this->smsaCodCharge($order->cod_amount);
                    }
                    if ($is_return) {
                        $price = $price + $this->smsaReturnChargeIn($order);
                    }
                } else {
                    if ($order->cod_amount > 0 && $is_return == 0) {

                        $price = $price + $this->smsaCodCharge($order->cod_amount);
                    }
                    if ($order->order_status == 'Returned') {
                        $price = $price + $this->smsaReturnChargeIn($order);
                    }
                }
                $order->carrier_charge = $price;


            } else {
                //where city !=  riyadh Damad Jeddah
                $price = 22;

                if ($order->weight > 15) {

                    $extra_weight = $order->weight - 15;
                    $extra_price = $extra_weight * 1;
                    $price = $price + $extra_price;

                    if ($order->cod_amount > 0 && $is_return == 0) {

                        $price = $price + $this->smsaCodCharge($order->cod_amount);

                    }
                    if ($is_return) {
                        $price = $price + $this->smsaReturnChargeout($order);

                    }
                } else {

                    if ($order->cod_amount > 0 && $is_return == 0) {
                        $price = $price + $this->smsaCodCharge($order->cod_amount);

                    }

                    if ($is_return) {
                        $price = $price + $this->smsaReturnChargeout($order);
                    }

                }

            }

            $order->carrier_charge = $price;
            $order->save();
        }
    }

    private function smsaCodCharge($cod)
    {

        if ($cod > 1 && $cod <= 1000) {
            return 6;
        } elseif ($cod > 1000 && $cod <= 3750) {
            return 8;
        } elseif ($cod > 3750) {
            return $cod * .01;
        }
    }

    private function smsaReturnChargeIn($order)
    {
        if ($order->weight > 15) {
            $price = 6;
            $extra_weight = $order->weight - 15;
            $extra_price = $extra_weight * 1;
            $price = $price + $extra_price;
            return $price;
        } else {
            return 6;
        }
    }

    public function smsaReturnChargeout($order)
    {
        //out this city  riyadh Damad Jeddah
        if ($order->weight > 15) {
            $price = 66;
            $extra_weight = $order->weight - 15;
            $extra_price = $extra_weight * 1;
            $price = $price + $extra_price;
            return $price;
        } else {
            return 6;
        }
    }

    public function testShipment()
    {
        $this->shipment_in();
    }

    public function indexx()
    {

        $wcppScript = WebClientPrint::createWcppDetectionScript(action('WebClientPrintController@processRequest'), Session::getId());

        return view('home.index', ['wcppScript' => $wcppScript]);
    }

    public function printFiles()
    {
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintFilesController@printMyFiles'), Session::getId());

        return view('home.printFiles', ['wcpScript' => $wcpScript]);
    }

    private function store_inurruptued($order, $store, $issue)
    {
        $interrupted_orders_table =
            interrupted_orders::where('shipping_number', $order->MLVID)
                ->first();
        if (!$interrupted_orders_table) {
            $interrubted_orders = new interrupted_orders();
            $interrubted_orders->shipping_number = $order->MLVID;
            $interrubted_orders->order_number = $order->orderNum;
            $interrubted_orders->carrier = $order->shipping_carrier;
            $interrubted_orders->store = $store->name;
            $interrubted_orders->issue = $issue;
            $interrubted_orders->country = $order->custCountry;
            $interrubted_orders->save();
            Log::error($issue . $order->MLVID);

        }
    }
}
