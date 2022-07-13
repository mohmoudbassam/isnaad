<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class deliverd_orders extends Model
{
    protected $table='deliverd_orders';
    protected $fillable=[
        'order_id'
    ];
    public $timestamps=false;


}
