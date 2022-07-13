<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_printed extends Model
{
    protected $table='order_printed';
    protected $fillable=[
        'order_id','count'
    ];
    public $timestamps=false;
}
