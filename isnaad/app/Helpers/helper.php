<?php


namespace App\Helpers;


trait helper
{

    public function validate_phone($number,$country){
             $number=   str_replace(' ', '', $number);
            $number=   str_replace('+', '', $number);
        if($country=='SA'){
            if(strlen($number)==14 ){
                if(substr($number,0,3)=='009'){
                    return true;
                }else{
                    return false;
                }
            }
            if(strlen($number)==12 ){
                if(substr($number,0,3)=='966'){
                    return true;
                }else{
                    return false;
                }
            }elseif(strlen($number)==10){
                if(substr($number,0,2)=='05'){
                    return true;
                }else{
                    return false;
                }
            }elseif(strlen($number)==9){
                if(substr($number,0,1)=='5'){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }
}
