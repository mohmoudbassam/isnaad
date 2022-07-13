<?php

namespace App\Http\Controllers\Orders;


use App\Classes\AramexAPI;
//use App\Exports\interrupted;
use App\interrupted_orders;
use App\Http\Controllers\Controller;

use App\Http\Requests\addInternationalOrder;
use App\Http\Requests\internationalOrder;
use App\Models\box;
use App\order;
use App\store;
use http\Env\Response;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;


class InternationalOrders extends Controller
{

    public function index()
    {
        $boxes = box::all();
        return view('m_design.ManageOrders.internationalOrder', ['boxes' => $boxes]);
    }

    public function checkOrder(internationalOrder $request)
    {

        $order = $orders = \DB::connection('shipedge')->table('shipping_in')
            ->where('MLVID', $request->shippingNumber)->first();

        if (!$order) {

         return   response()->json(['errors'=>['order' => 'pleas enter a valid shipping number ']],422);
        }
        $orderProc=order::where([['shipping_number',$order->MLVID],['active','1']])->first();
        if($orderProc){
            return   response()->json(['errors'=>['order' => 'this order already  shipped']],422);
        }
        return response()->json(['order' => $order,]);
    }

    public function add_international(addInternationalOrder $request)
    {
        $boxes = box::all();
        
        $array_for_boxes = [];
        $order = \DB::connection('shipedge')->table('shipping_in')
            ->where('MLVID', $request->shippingNumber)->first();
           
        if (!$order) {
            return redirect()->back()->withErrors([
                'order ' => 'pleas enter a valid shipping number'
            ]);
        }
        if ($request->has('length') && $request->get('length') != '') {
            $height = $request->get('height');
            $length = $request->get('length');
            $width = $request->get('width');
            $customBoxWeigh = $height * $length * $width;
            $customBoxAcutalWeigh = $customBoxWeigh / 5000;
            $customBoxChargableWeigh = $customBoxWeigh / 3500;
            $mainWeight = $request->weight;

        }


        //dd($array_for_boxes);

        if ($request->has('gr')) {
            $array_for_boxes = DB::select('SELECT t.* FROM box t JOIN' . $this->getBoxes($request->gr) . ' x ON x.ID = t.ID');

        }


        if ($request->has('length') && $request->get('length') != '') {

            $array_for_boxes[] = (object)[
                'box_weight' => $customBoxWeigh,
                'isnaad_weight' => $customBoxChargableWeigh,
                'aramex_weight' => $customBoxAcutalWeigh
            ];
        }

        // dd($array_for_boxes);
        // $boxesForOrder=  box::whereIn('id',$array_for_boxes)->get();

        
        if ($request->weight != $order->WeightSum) {
            $order->WeightSum = $request->weight;
        }
        $boxesForOrder = Collect($array_for_boxes);
        //  dd($boxesForOrder);
        if ($request->length == null) {
            $mainWeight = $boxesForOrder->sum('box_weight') + $order->WeightSum;
           // dd($mainWeight,$boxesForOrder->sum('box_weight'));
        }
       
        //  dd($boxesForOrder,$order->WeightSum,$mainWeight);
        $chargalbeWeight = count($array_for_boxes) * $boxesForOrder->max('isnaad_weight');
        $actulWeight = $boxesForOrder->sum('aramex_weight');
        $actulWeight = max($mainWeight, $actulWeight);
        //   dd($mainWeight,$chargalbeWeight,$actulWeight,$boxesForOrder);
        $chargalbeWeight = max($mainWeight, $chargalbeWeight);
        $store = store::where('account_id', $order->AcountID)->first();
        //   dd($chargalbeWeight,$actulWeight);
        $order->WeightSum = $actulWeight;
        ////
        $order->sender_name = $store->name;
        $order->contact_name = $store->contact_person;
        $order->sender_email = $store->email;
        $order->sender_phone = $store->phone;
        $order->numberOfBoxInternatonal=count($array_for_boxes);
        //dd($order->numberOfBoxInternatonal);
        ///
        /// ///
        $shipment = AramexAPI::create_shipment($order);
        $order->actulWeight = $actulWeight;
        $order->chargalbeWeight = $chargalbeWeight;
        $order->WeightSum = $request->weight;
        $this->insertOrder($order, $store, $shipment,$actulWeight);
        interrupted_orders::where('shipping_number',$order->MLVID)->delete();
        return redirect()->back()->with(['suc' => 'order shipped ']);
    }

    public function getBoxes($boxes)
    {
        $str = '( SELECT 0 AS ID ';
        foreach ($boxes as $box) {

            $str .= 'UNION ALL SELECT ' . $box[1] . ' AS ID ';
        }
        $str .= ' )';
        return $str;
    }

    public function insertOrder($order, $store, $shipment,$mainWeight)
    {

        $order_data = array(
            'carrier' => 'Aramex',
            'ship_method' => '',
            'tracking_number' => $shipment['tracking_number'],
            'cod_amount' => $order->CODamount,
            'awb_url' => $shipment['waybill_url'],
            'store_id' => $order->AcountID,
            'shipping_number' => $order->MLVID,
            'order_number' => $order->orderNum,
            // 'shipping_charge' =>
            'shipping_charge' => '',
            'cod_charge' => ($order->CODamount > 0) ? $store->cod_charge : 0,
            'processing_status' => 1,
            'delivery_status' => 0,
            'processing_date' => $store->processing_date,
            'weight' => $mainWeight,
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
            'carrier_charge' => 0,
            'actulWeight' => $order->actulWeight,
            'chargalbeWeight' => $order->chargalbeWeight,

        );
        order::create($order_data);
    }
}
