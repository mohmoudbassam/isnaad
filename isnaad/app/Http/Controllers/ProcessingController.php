<?php

namespace App\Http\Controllers;

use App\carrier;
use App\carrier_city;
use App\city;
use App\city_name;
use App\Classes\Aja;
use App\Classes\AramexAPI;
use App\Classes\Aymakan;
use App\Classes\BARQ;
use App\Classes\DOS;
use App\Classes\FDA;
use App\Classes\Forrun;
use App\Classes\isnaad_wbl;
use App\Classes\Jones;
use App\Classes\Kudhha;
use App\Classes\LaBaih;
use App\Classes\Lastpoint;
use App\Classes\Mahmoul;
use App\Classes\Mkhdoom;
use App\Classes\MORA;
use App\Classes\Naqel;
use App\Classes\RedBox;
use App\Classes\Sama;
use App\Classes\Shipox;
use App\Classes\Smsa;
use App\Classes\Tamex;
use App\Classes\UPS;
use App\Classes\Wadha;
use App\Classes\Weenk;
use App\constans;
use App\Helpers\carrier_charge;
use App\Helpers\Carrier_Shipment;
use App\Helpers\helper;
use App\Helpers\store_shipment;
use App\Http\Requests\internationalOrder;
use App\interrupted_orders;
use App\Models\box;
use App\Models\carrire_shipment;
use App\order;
use App\store;
use App\store_carrier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;
use App\Models\order_box;
use Neodynamic\SDK\Web\WebClientPrint;

class ProcessingController extends Controller
{
    use helper, store_shipment, carrier_charge, Carrier_Shipment;

    public function index()
    {
        $boxes = box::all();
        $carriers = carrier::all();
        $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());
        return view('m_design.ManageOrders.make_processing', ['boxes' => $boxes, 'carriers' => $carriers,'wcpScript' => $wcpScript]);
    }

    public function check_order(Request $request)
    {

        $order = DB::connection('shipedge')->table('shipping_in')
            ->where('MLVID', $request->shippingNumber)->first();

        if ($order == null) {
            return response()->json(['errors' => ['order' => 'order not found ']], 422);
        }
        $orderProc = order::where([['shipping_number', $order->MLVID], ['active', '1']])->first();
        if ($orderProc) {
            return response()->json(['errors' => ['order' => 'this order already  processing']], 422);
        }


        $carriers = carrier::all();

        $store = store::where('account_id', $order->AcountID)->first();
        $order->custCity = trim($order->custCity);
        $order->custCity = str_replace('\\', '', $order->custCity);
        $city = city::where('name', $order->custCity)->first();
        $city_id = '';
        if ($city) {
            $order->custCity = $city->name;
            $city_id = $city->id;
        } else {
            $city_name = city_name::where('name', $order->custCity)->first();

            if ($city_name) {
                $city = city::where('id', $city_name->city_id)->first();
                $order->custCity = $city->name;
                $city_id = $city->id;

            } else {
                $interrupted_orders_table =
                    interrupted_orders::where('shipping_number', $order->MLVID)
                        ->first();
                if (!$interrupted_orders_table) {

                    $this->SaveInterrupt($order, 'Address', $store);
                    return response()->json(['errors' => ['order' => 'this order is in the interrupted']], 422);

                }

            }
        }

        if ($order->custCity == 'riyadh' || $order->custCity == 'Riyadh') {
            $carrier = $this->shipment_in_carrier($order);
        } else {

            $city = city::where('name', $order->custCity)->first();
            if (!$city) {
                $this->SaveInterrupt($order, 'Address', $store);
                return response()->json(['errors' => ['order' => 'this order is in the interrupted']], 422);
            }

            $city_id = $this->getCityID($city, $order);
            $carrier = $this->shipment_out_carrer($order, $city_id);
        }

        if (!$order) {
            return response()->json(['errors' => ['order' => 'pleas enter a valid shipping number ']], 422);
        }


        return response()->json(
            [
                'order' => $order,
                'carrier' => $carrier,
                'carriers' => $carriers
            ]
        );
    }

    public function order_shipping(Request $request)
    {


        $order = DB::connection('shipedge')->table('shipping_in')
            ->where('MLVID', $request->shippingNumber)
            ->where('MLVID', 'NOT LIKE', 'rep%')
            ->first();


        $store = store::where('account_id', $order->AcountID)->first();

        $order->shipping_carrier = $request->carrier;
        $carrer = Carrier::where('name', $order->shipping_carrier)->first();

        $carrer_multiple = $order->custCountry == 'SA' ? $carrer->multiple_sa : $carrer->multiple_out_sa;


        $boxes = [];

        $order->WeightSum = $request->weight;
        if ($request->gr) {

            $boxes_collection = collect($request->gr);

            $boxes_ids = collect($boxes_collection)->map(function ($box) {
                return $box['box'];
            })->toArray();

            $boxes = box::query()->whereIn('id', $boxes_ids)->get();
            $box_map = $boxes->mapWithKeys(function ($item) use ($boxes_collection) {
                return [$item->id => $boxes_collection->where('box', $item->id)->first()['qty']];
            });
            $boxes = $boxes->map(function ($box) use ($boxes_collection) {

                return $box->setAttribute('quantity', $boxes_collection->where('box', $box->id)->first()['qty']);
            });
        }

        if ($request->has('length') && $request->get('length') != '') {
            $h = $request->get('height');

            $l = $request->get('length');
            $w = $request->get('width');
            $customBoxWeigh = $h * $l * $w;
            $customBoxAcutalWeigh = $customBoxWeigh / $carrer_multiple;
            $customBoxChargableWeigh = $customBoxWeigh / 3500;//*isnaad

            $boxes[] = (object)[
                'box_weight' => $customBoxWeigh,
                'isnaad_weight' => $customBoxChargableWeigh,
                'carrier_weight' => $customBoxAcutalWeigh,
                'l' => $l,
                'h' => $h,
                'w' => $w,
                'manual' => true,

            ];

        }


        $custom_box = $boxes->where('manual', true)->first();

        $mainWeight = $custom_box ? $custom_box->carrier_weight : $boxes->filter(function ($box) {///////////الوزن الفعلي على الميزان في كل مكان
                return $box->manual != true;
            })->sum(function ($box) {

                return $box->box_weight * $box->quantity;
            }) + $order->WeightSum;

        $chargalbeWeightIsnaad = $custom_box ? $customBoxChargableWeigh : $boxes->sum(function ($box) {
                return 1 * $box->quantity;
            }) * $boxes->max('isnaad_weight');              ////////دايمنشن ويت تبع اسناد الطول في العرض في الارتفاع
        $actulWeightCarrer = $custom_box ? $customBoxAcutalWeigh : $boxes->sum(function ($box) use ($carrer_multiple) { //////////للكرير
            return ($box->l * $box->w * $box->h) / $carrer_multiple;
        });
        $isnaad_weight_for_invoice = max($mainWeight, $chargalbeWeightIsnaad);

        $actulWeightCarrer_final = max($mainWeight, $actulWeightCarrer);///////////ينبعت للكرير

        $order->WeightSum = $actulWeightCarrer_final;
        if ($request->is_divide) {
            $order->isDevide = 1;
            $order->newQty = $request->quantity_divide;
        }

        $order = $this->getOrdersFromShipedge($order);

        if (is_array($order)) {
            return response()->json($order, 200);
        }
        $order->id = 20;
        foreach ($box_map as $box_id => $qty) {

            for ($i = 1; $i <= $qty; $i++) {

                order_box::create([
                    'box_id' => $box_id,
                    'order_id' => $order->id
                ]);
            }
        }

        $order->Qty_Item = $request->quantity;
        $boxes->each(function ($box) use ($order) {

        });

        $order->weight = $isnaad_weight_for_invoice;
        $order->actulWeight = $mainWeight;
        $order->chargalbeWeight = $chargalbeWeightIsnaad;

        return response()->json([
            'success' => true,
            'message' => 'order has been shipped successfully'
        ]);
    }

    public function getOrdersFromShipedge($order)
    {

        $app_order =
            order::where('shipping_number', $order->MLVID)
                ->where('active', 1)->first();

        if ($app_order) {

            return [
                'status' => 400,
                'message' => 'this order already shipped '
            ];
        }


        $carrier = $order->shipping_carrier;

        $store = store::where('account_id', $order->AcountID)->first();

        $order->sender_name = $store->name;
        $order->contact_name = $store->contact_person;
        $order->sender_email = $store->email;
        $order->sender_phone = $store->phone;
        $order->CODamount = ($order->CODamount) ? $order->CODamount : 0;


        $shipment = [];
        $is_auto_ship = 0;
        $shipment = $this->carrier($carrier, $order);

        $status = $shipment['shipment']['status'] ?? null;

        if (isset($status)) {

            if ($status == 'success') {

                if ($order->custCountry != 'SA') {
                    if ($order->WeightSum > $store->weight_out_sa) {
                        $diff = $order->WeightSum - $store->weight_out_sa;
                        $dif_tot = $diff / $store->weight_out_sa;
                        $total = $dif_tot * $store->add_cost_out_sa;
                        $shipping_charge = $store->shipping_charge_international + $total;
                    } else {
                        $shipping_charge = $store->shipping_charge_international;

                    }
                } else {
                    if ($order->custCity == 'Riyadh') {
                        if ($order->WeightSum > $store->weight_in_sa) {
                            $diff = $order->WeightSum - $store->weight_in_sa;
                            $total = $diff * $store->add_cost_in_sa;
                            $shipping_charge = $store->shipping_charge_in_ra + $total;
                        } else {
                            $shipping_charge = $store->shipping_charge_in_ra;

                        }
                    } else {
                        if ($order->WeightSum > $store->weight_in_sa) {
                            $diff = $order->WeightSum - $store->weight_in_sa;
                            $total = $diff * $store->add_cost_in_sa;
                            $shipping_charge = $store->shipping_charge_out_ra + $total;
                        } else {
                            $shipping_charge = $store->shipping_charge_out_ra;

                        }
                    }
                }


                $order_data = array(
                    'carrier' => $order->shipping_carrier,
                    'ship_method' => $shipment['ship_method'],
                    'tracking_number' => $shipment['shipment']['tracking_number'],
                    'cod_amount' => $order->CODamount,
                    'awb_url' => $shipment['shipment']['waybill_url'],
                    'store_id' => $order->AcountID,
                    'shipping_number' => $order->MLVID,
                    'order_number' => $order->orderNum,
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
                return order::create($order_data);

            } else {

                $this->SaveInterrupt($order, 'Issue From Carrier', $store);
                return [
                    'status' => false,
                    'message' => 'this order gone to interrubted orders '
                ];
            }
        } else {
            $this->SaveInterrupt($order, 'Issue From Carrier', $store);
            return [
                'status' => false,
                'message' => 'this order gone to interrubted orders '
            ];
        }


    }

    private function SaveInterrupt($order, $issue, $store)
    {
        interrupted_orders::where('shipping_number', $order->MLVID)->delete();

        $interrubted_orders = new interrupted_orders();
        $interrubted_orders->shipping_number = $order->MLVID;
        $interrubted_orders->order_number = $order->orderNum;
        $interrubted_orders->carrier = $order->shipping_carrier;
        $interrubted_orders->store = $store->name;
        $interrubted_orders->issue = $issue;
        $interrubted_orders->save();
        Log::error('issue in status' . $order->MLVID);

    }

    public function carrier($carrier, $order)
    {

        if ($carrier == "Aramex") {

            $shipment = AramexAPI::create_shipment($order);
            $carrier = 'Aramex';
            $ship_method = "Aramex";

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
            $shipment = Kudhha::create_shipment($order);
            $carrier = 'Kudhha';
            $ship_method = "Kudhha";

        } elseif ($carrier == "Smsa") {
            $shipment = Smsa::create_shipment($order);
            $carrier = 'Smsa';
            $ship_method = "Smsa";
        } elseif ($carrier == "PICK") {

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

        } elseif ($carrier == "Weenk") {
            $shipment = Weenk::create_shipment($order);
            $carrier = 'Weenk';
            $ship_method = "Weenk";

        } elseif ($carrier == "UPS") {
            $shipment = UPS::create_shipment($order);
            $carrier = 'UPS';
            $ship_method = "UPS";

        } elseif ($carrier == "Aja") {
            $shipment = Aja::create_shipment($order);
            $carrier = 'Aja';
            $ship_method = "Aja";

        } elseif ($carrier == "RedBox") {
            $shipment = RedBox::create_shipment($order);
            $carrier = 'RedBox';
            $ship_method = "RedBox";

        }
        return [
            'shipment' => $shipment,
            'carrier' => $carrier,
            'ship_method' => $ship_method
        ];
    }

    public function shipment_in_carrier($order, $pr = 0)
    {

        $orderQuery = order::query();
        $countInRyad = $orderQuery->Active()->Riyadh()->OnThisDay()->count() % 100;
        $carrire_shipments = carrire_shipment::query()->InRiyadh()->get();
        $carrire_shipment = $carrire_shipments->where('from_num', '<=', $countInRyad)->where('to_num', '>=', $countInRyad)->first();
        $countweenk = order::where([['carrier', 'Weenk'], ['created_at', Carbon::now()->format('Y-m-d')]])->count();
        $countjones = order::where([['carrier', 'Jones'], ['created_at', Carbon::now()->format('Y-m-d')], ['city', 'Riyadh']])->count();

        if ($carrire_shipment->carrier_name == 'Weenk' && $countweenk >= 100) {
            //return $this->shipment_in($order, $carrire_shipment->priority + 1);
            return 'Kudhha';
        }
        if ($carrire_shipment->carrier_name == 'Jones' && $order->WeightSum > 15) {
            //return $this->shipment_in($order, $carrire_shipment->priority + 1);
            return 'Kudhha';
        }
        if ($carrire_shipment->carrier_name == 'Jones' && $countjones >= 50) {
            //return $this->shipment_in($order, $carrire_shipment->priority + 1);
            return 'Kudhha';
        }
        return $carrire_shipment->carrier_class;


    }

    public function shipment_out_carrer($order, $city_id, $pr = 0, $lob = 0)
    {
        $carrier_citymkhdoom = carrier_city::where([['carrier_id', '1'], ['city_id', $city_id]])->first();
        if ($carrier_citymkhdoom) {
            return 'Mkhdoom';
        }

        if ($order->custCity == 'Makkah' || $order->custCity == 'Taif' || $order->custCity == 'Qatif' || $order->custCity == 'Safwa' || $order->custCity == 'Seihat' || $order->custCity == 'Ras Tanura') {
            return 'Jones';

        }

        if ($order->CODamount > 0 && $order->CODamount < 250) {
            if ($this->check_city_carrier($city_id, 4) && $order->AcountID != 48 && $order->AcountID != 58) {

                return 'Aymakan';

            } /*
             elseif ($this->check_city_carrier($city_id, 24) && $order->AcountID != 13) {
                return [
                    'shipment' => Aja::create_shipment($order),
                    'carrier' => 'Aja',
                    'shiped_methode' => 'Aja'
                ];

            } */
            elseif ($this->check_city_carrier($city_id, 23) && $order->WeightSum < 70) {
                return 'UPS';
            } else {
                return 'Aramex';
            }
        }

        if ($order->CODamount > 250) {
            /*
                        if ($this->check_city_carrier($city_id, 24) && $order->AcountID != 13) {
                            return [
                                'shipment' => Aja::create_shipment($order),
                                'carrier' => 'Aja',
                                'shiped_methode' => 'Aja'
                            ];

                        }*/
            if ($this->check_city_carrier($city_id, 23) && $order->WeightSum < 70) {
                return 'UPS';
            } else {
                return 'Aramex';
            }
        }

        $orderQuery = order::query();
        $countOutRyad = $orderQuery->Active()->OutRiyadh()->OnThisDay()->count() % 200;

        if ($pr == 8) {
            $pr = 1;
        }
        $pr = $pr % 8;
        $lob = $lob % 8;

        $carrire_shipment = carrire_shipment::query()->OutRiyadh()->get();

        if ($pr == 0 && $lob == 0) {
            $carrire_shipment = $carrire_shipment->where('from_num', '<=', $countOutRyad)->where('to_num', '>=', $countOutRyad)->first();

            $carrier = carrier::where('name', $carrire_shipment->carrier_name)->first();
        } else {
            $carrire_shipment = $carrire_shipment->where('priority', $pr)->first();

            $carrier = carrier::where('name', $carrire_shipment->carrier_name)->first();

        }

        if ($carrire_shipment->carrier_name == 'Aramex') {

            if ($this->check_city_carrier($city_id, 4) && $order->AcountID != 48 && $order->AcountID != 58) {

                return 'Aymakan';
            } elseif ($this->check_city_carrier($city_id, 23) && $order->WeightSum < 70) {
                return 'UPS';

            } else {
                return 'Aramex';
            }
            //  return $this->create_shipment($order, $carrire_shipment);
        }

        if ($carrire_shipment->carrier_name == 'Smsa') {
            return 'Smsa';

        }

        if ($carrire_shipment->carrier_name == 'Naqel') {
            return 'Naqel';

        }

        if ($carrire_shipment->carrier_name == 'Aymakan' && $order->AcountID == 48) {
            return $this->shipment_out_carrer($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
        }

        if (!$this->check_city_carrier($city_id, $carrier->id)) {
            return $this->shipment_out_carrer($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
        }

        return $carrire_shipment;
    }

    public function getCityID($city, $order)
    {
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
            }
        }
        return $city_id;
    }


    public function sendFcmNotification($id){

        $client = new \GuzzleHttp\Client();
        $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json');
        $data=[
            'id'=>5
        ];
        $data=  json_encode($data);
        $res = $client->post('https://apis.isnaad.sa/api/v1/notification', [
            'headers' => $header,
            'body' => $data
        ]);
         $res->getStatusCode();
         $res->getBody();
    }

}
