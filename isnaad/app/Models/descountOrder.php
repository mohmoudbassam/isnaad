<?php

namespace App\Models;
use App\order;
use App\store;
use Illuminate\Database\Eloquent\Model;

class descountOrder extends Model
{
    protected $table='descountOrder';
    
    public function order(){
        return $this->hasOne(order::class , 'order_number','order_number');
    }
}
