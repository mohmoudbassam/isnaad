<?php


namespace App\Helpers;


use App\carrier;
use App\carrier_city;
use App\Classes\Forrun;
use App\Classes\Naqel;
use App\Models\aramex_charge;
use App\Models\carrire_shipment;
use App\Models\naqel_city;
use App\order;
use Illuminate\Support\Facades\Log;
use App\Classes\AramexAPI;
use App\Classes\Mkhdoom;
use App\Classes\Smsa;
use App\Classes\Beez;
use App\Classes\DOS;
use App\Classes\Shipox;
use App\Classes\Zajil;
use App\Classes\Wadha;
use  App\Classes\LaBaih;
use App\Classes\Aymakan;
use App\Classes\BARQ;
use App\Classes\FDA;
use App\Classes\Tamex;
use App\Classes\Lastpoint;
use App\Classes\UPS;

trait Carrier_Shipment
{
    public static $PATH = 'App\Classes\\';

    public function shipment_in($order, $pr = 0)
    {
//dd(333);
        $orderQuery = order::query();
        $countInRyad = $orderQuery->Active()->Riyadh()->OnThisDay()->count() % 200;

        $carrire_shipments = carrire_shipment::query()->InRiyadh()->get();


        $carrire_shipment = $carrire_shipments->where('from_num', '<=', $countInRyad)->where('to_num', '>=', $countInRyad)->first();

        // dd( $carrire_shipment );
//dd($countInRyad,$carrire_shipment);

        if($carrire_shipment->carrier_name=='FDA' && $order->AcountID ==74){
            //return $this->shipment_in($order, $carrire_shipment->priority + 1);
            return[
                'shipment' => Wadha::create_shipment($order),
                'carrier' => 'Wadha',
                'shiped_methode' => 'Wadha'
            ];
        }

        if($carrire_shipment->carrier_name=='Kudhha' && $order->CODamount>0){
            //return $this->shipment_in($order, $carrire_shipment->priority + 1);
            return[
                'shipment' => Wadha::create_shipment($order),
                'carrier' => 'Wadha',
                'shiped_methode' => 'Wadha'
            ];
        }

        return $this->create_shipment($order, $carrire_shipment);

    }


    public function shipment_out($order, $city_id, $pr = 0, $lob = 0)
    {
        //dd(222);
        /*
        if($order->AcountID==43){
            $count=  order::where([['store_id','43']])->Active()->OutRiyadh()->OnThisDay()->count();
            if($count%2==0 && $order->CODamount ==0){
                return [
                    'shipment' => Smsa::create_shipment($order),
                    'carrier' => 'Smsa',
                    'shiped_methode' => 'Smsa'
                ];
            }else{

                return [
                    'shipment' => AramexAPI::create_shipment($order),
                    'carrier' => 'Aramex',
                    'shiped_methode' => 'EAMXDOM'
                ];
            }
        }
        */

        if($order->CODamount>0){
            if($this->check_city_carrier($city_id,23) && $order->WeightSum < 70){
                
                return [
                    'shipment' => UPS::create_shipment($order),
                    'carrier' =>'UPS',
                    'shiped_methode' =>'UPS'
                ];
            }elseif($this->check_city_carrier($city_id,4) && $order->AcountID != 48 && $order->AcountID != 58){
                return [
                    'shipment' => Aymakan::create_shipment($order),
                    'carrier' =>'Aymakan',
                    'shiped_methode' =>'Aymakan'
                ];
            }
            else{
                return [
                    'shipment' =>AramexAPI::create_shipment($order),
                    'carrier' => 'Aramex',
                    'shiped_methode' => 'EAMXDOM'
                ];
            }
        }



$orderQuery = order::query();
$countOutRyad = $orderQuery->Active()->OutRiyadh()->OnThisDay()->count() % 200;
//dd($countOutRyad);
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
/*
if($carrire_shipment->carrier_name=='Smsa'){
    if($order->CODamount ==0){
        return $this->create_shipment($order, $carrire_shipment);
    }else{
        return [
            'shipment' => AramexAPI::create_shipment($order),
            'carrier' => 'Aramex',
            'shiped_methode' => 'EAMXDOM'
        ];
    }
    //  return $this->create_shipment($order, $carrire_shipment);
}
*/
if($carrire_shipment->carrier_name=='Aramex'){
    return [
        'shipment' => AramexAPI::create_shipment($order),
        'carrier' => 'Aramex',
        'shiped_methode' => 'EAMXDOM'
    ];
    //  return $this->create_shipment($order, $carrire_shipment);
}

if($carrire_shipment->carrier_name=='Smsa'){
    return [
        'shipment' => Smsa::create_shipment($order),
        'carrier' => 'Smsa',
        'shiped_methode' => 'Smsa'
    ];
    //  return $this->create_shipment($order, $carrire_shipment);
}

if($carrire_shipment->carrier_name=='Naqel'){
    $shpment=  Naqel::create_shipment($order);
    // dd( $shpment);
    if($shpment){
        return [
            'shipment' => $shpment,
            'carrier' => 'Naqel',
            'shiped_methode' => 'Naqel'
        ];
    }else{
        return false;
    }
}
if($carrire_shipment->carrier_name=='Aymakan' && $order->AcountID == 48 ){
    return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
}
/*
       if($carrire_shipment->carrier_name=='Aymakan' && $order->AcountID == 58 ){
        return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
        }

        if($carrire_shipment->carrier_name=='LaBaih' && $order->AcountID == 58 ){
        return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
        }

        if($carrire_shipment->carrier_name=='Mahmoul' && $order->AcountID == 58 ){
        return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
        }
*/
if($carrire_shipment->carrier_name=='UPS' && $order->WeightSum > 70){
    return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
}

if (!$this->check_city_carrier($city_id, $carrier->id)) {
    return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
}
/*
if($carrire_shipment->carrier_name=='Tamex'&&$order->CODamount !=0){

    return $this->shipment_out($order, $city_id, $carrire_shipment->priority + 1, $lob + 1);
}
*/
return $this->create_shipment($order, $carrire_shipment);
}

private function check_city_carrier($city_id, $carrier_id)
{

    $carrier_city = carrier_city::where([['carrier_id', $carrier_id], ['city_id', $city_id]])->first();

    return $carrier_city ? true : false;

}

private function create_shipment($order, $carrire_shipment)
{


    $class_called = self::$PATH . $carrire_shipment->carrier_class;

    return [
        'shipment' => $class_called::create_shipment($order),
        'carrier' => $carrire_shipment->carrier_name,
        'shiped_methode' => $carrire_shipment->shiped_methode
    ];
}

private function check_naqel_city($city){
    $naqel_city= naqel_city::where([['CityName', $city]]);
    if($naqel_city){
        return $naqel_city->naqel_name;
    }else{
        return false;
    }
}

}
