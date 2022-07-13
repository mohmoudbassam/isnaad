<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class interrupted_orders extends Model
{
    protected $table = 'interrupted_orders';
    public $timestamps= true;
    protected $fillable  = [

        'shipping_number',
        'order_number',
        'carrier',
        'store',
        'issue',
        'country'
        ];

}
