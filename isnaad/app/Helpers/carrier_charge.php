<?php


namespace App\Helpers;


use App\carrier;
use App\Models\aramex_charge;
use App\Models\aramex_city;
use Illuminate\Support\Facades\Log;

trait carrier_charge
{
    public function carrier_charge  ($carrier,$order){

        if($carrier=='Mkhdoom'){

            return $this->MakhdoomCh($order);
        }
        $carrierOb=carrier::where('name',$carrier)->first();

        if(isset($carrierOb->charges_base)){

            $ok = 1;

        }else{
            Log::error('carrier: '.$carrier.' order id: '.$order->MLVID);
        }


        if($carrierOb->charges_base==0){
            return  $this->$carrier($carrierOb,$order);
        }else{

            return 0;
        }

    }

    private function Aymakan($carrier,$order){
        $extra_weight=0;
        $price_of_extra_weight=0;
        if($order->order_status=='Returned' || $order->return_date_carrier != null){
            return  [
                9,$extra_weight,$price_of_extra_weight
            ];
        }

        if($order->city=='riyadh'){

            $price=$carrier->price_in_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{
            $price=$carrier->price_out_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight = $order->weight-$carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        if($order->cod_amount>0){
            $codCharge= $order->cod_amount * .008;
            $last_price= $codCharge+$last_price;
        }

        return  [
            $last_price,$extra_weight,$price_of_extra_weight
        ];

    }

    private function Tamex($carrier,$order){
        $extra_weight=0;$price_of_extra_weight=0;
        if($order->city=='riyadh'){

            $price=$carrier->price_in_ryad;

            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 2;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{

            $price=$carrier->price_out_ryad;

            if($carrier->allowed_weight_in<$order->weight){

                $extra_weight =  $order->weight - $carrier->allowed_weight_in ;

                $price_of_extra_weight =$extra_weight * 2;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        return  $last_price;

    }

    private function Zajil($carrier,$order){

        if($order->custCity=='riyadh'){

            $price=$carrier->price_in_ryad;

            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 2;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{

            $price=$carrier->price_out_ryad;

            if($carrier->allowed_weight_in<$order->weight){

                $extra_weight =  $order->weight - $carrier->allowed_weight_in ;

                $price_of_extra_weight =$extra_weight * 2;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }
        if($order->CODamount>0){
            $last_price= 2+$last_price;
        }

        return  $last_price;

    }

    private function BARQ($carrier,$order){

        if($order->custCity=='riyadh'){

            $price=$carrier->price_in_ryad;

            if($carrier->allowed_weight_in<$order->WeightSum){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 0;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{

            $price=$carrier->price_out_ryad;

            if($carrier->allowed_weight_in<$order->WeightSum){

                $extra_weight =  $order->weight - $carrier->allowed_weight_in ;

                $price_of_extra_weight =$extra_weight * 0;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        if($order->CODamount>0){
            $last_price= 5+$last_price;
        }

        return  $last_price;

    }

    private function Aramex($carrier,$order){

        $price_of_extra_weight=0;
        $extra_weight=0;

        $city=  aramex_city::where('name',$order->city)->first();

        if($order->country =='SA'){
            if($order->city=='Riyadh'){
                $price=20;
            }else{
                $price=21;
            }


            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight = $order->weight-$carrier->allowed_weight_in ;
                $price_of_extra_weight =$extra_weight *1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;

            }

        }else{
            if($order->created_at->format('Y-m')>='2021-06'){
                $weight='actulWeight';
            }else{
                $weight='weight';
            }

            $aramex_charge= aramex_charge::where('country_cod',$order->country)->first();
            $orderWeight=$order->{$weight};
            // dd( $orderWeight);
            if( $orderWeight < .5){
                try {
                    $last_price=$aramex_charge->first_half;
                }catch (\Exception $exception){
                    return 0;
                }

            }elseif ($orderWeight > .5 && $orderWeight <= 10){
                try {
                    $last_price=$aramex_charge->first_half;
                }catch (\Exception $exception){
                    return 0;
                };
                $extra_weight = $orderWeight-.5 ;
                $extra_weight_in_half=$extra_weight/.5;
                $extra_weight_in_half_price=$extra_weight_in_half*$aramex_charge->additional_afrer_half;
                $last_price=$last_price+$extra_weight_in_half_price;

            }elseif($orderWeight > 10 && $orderWeight <= 15){
                $last_price=$aramex_charge->first_half;
                $extra_weight = $orderWeight-.5 ;
                $extra_weight_in_half=$extra_weight/.5;
                $extra_weight_in_half_price=$extra_weight_in_half*$aramex_charge->additonal_after_ten;
                $last_price=$last_price+$extra_weight_in_half_price;
            }elseif($orderWeight> 15){
                $last_price=$aramex_charge->first_half;
                $extra_weight = $orderWeight -.5 ;

                $extra_weight_in_half=$extra_weight/.5;

                $extra_weight_in_half_price=$extra_weight_in_half*$aramex_charge->additonal_after_15;

                $last_price=$last_price+$extra_weight_in_half_price;
            }else{
                $last_price=0;
            }
            $last_price=($last_price*.19)+$last_price;
        }

        return [
            $last_price,$price_of_extra_weight,$extra_weight
        ];
    }

    public function MakhdoomCh($order){
        //  dd('sd');
        $price_of_extra_weight=0;
        $extra_weight=0;

        $carrier=  carrier::where('name','Mkhdoom')->first();

        if($order->city=='riyadh' || $order->city=='Riyadh'){

            $price=$carrier->price_in_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 1.5;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{
            $price=$carrier->price_out_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight = $order->weight-$carrier->allowed_weight_in  ;
                $price_of_extra_weight =$extra_weight * 1.5;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        return  [
            $last_price,$price_of_extra_weight,$extra_weight
        ];
    }

    public function LaBaih( $carrier,$order){
        //  $carrier=  carrier::where('name','LaBaih')->first();
        $price_of_extra_weight=0;
        $extra_weight=0;
        if($order->city=='Riyadh'){

            $price=$carrier->price_in_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{
            $price=$carrier->price_out_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight = $order->weight-$carrier->allowed_weight_in  ;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        return [
            $last_price,$extra_weight,$price_of_extra_weight
        ];
    }

    public function Wadha( $carrier,$order){
          $carrier=  carrier::where('name','Wadha')->first();
        $price_of_extra_weight=0;
        $extra_weight=0;
        
        if($order->order_status=='Returned' || $order->return_date_carrier != null){
            return  [
                0,$extra_weight,$price_of_extra_weight
            ];
        }
            $price=$carrier->price_in_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * .75;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }
        return  [
            $last_price,$price_of_extra_weight,$extra_weight
        ];
       
    }
    public function FDA( $carrier,$order){
        return [
            16,0,0
        ];
    }
    public function Lastpoint( $carrier,$order){
        $price_of_extra_weight=0;
        $extra_weight=0;

        $carrier=  carrier::where('name','Lastpoint')->first();

        if($order->city=='riyadh' || $order->city=='Riyadh'){

            $price=$carrier->price_in_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{
            $price=$carrier->price_out_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight = $order->weight-$carrier->allowed_weight_in  ;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        return  [
            $last_price,$price_of_extra_weight,$extra_weight
        ];
    }

    public function UPS( $carrier,$order){
        $price_of_extra_weight=0;
        $extra_weight=0;

        $carrier=  carrier::where('name','UPS')->first();

        if($order->city=='riyadh' || $order->city=='Riyadh'){

            $price=$carrier->price_in_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight =   $order->weight - $carrier->allowed_weight_in;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }else{
            $price=$carrier->price_out_ryad;
            if($carrier->allowed_weight_in<$order->weight){
                $extra_weight = $order->weight-$carrier->allowed_weight_in  ;
                $price_of_extra_weight =$extra_weight * 1;
                $last_price = $price + $price_of_extra_weight;
            }else{
                $last_price=$price;
            }

        }

        return  [
            $last_price,$price_of_extra_weight,$extra_weight
        ];
    }

}
