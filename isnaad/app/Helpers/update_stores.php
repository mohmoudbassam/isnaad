<?php


namespace App\Helpers;


use App\Http\Controllers\integtation\StoresController;

class update_stores extends StoresController
{
    public  function update_stores($order_num,$store_id,$status){
       
    if($store_id == 5){
        
      $this->update_status_Sadatalbukhur($order_num,$status);
    }elseif ($store_id == 9){
        $this->update_status_JAWANI($order_num,$status);
    }elseif ($store_id == 10){
        $this->update_status_Snackches($order_num,$status);
    }elseif ($store_id == 11){
        $this->update_status_robil($order_num,$status);
    }elseif ($store_id == 13){
        $this->update_status_Sorrah($order_num,$status);
    }elseif ($store_id == 4){
        $this->update_status_wix($order_num,$status);
    }
    }
}
