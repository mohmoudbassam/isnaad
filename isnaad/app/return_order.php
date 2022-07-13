<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class return_order extends Model
{
    protected $table='return_order';
    protected $fillable=[
        'retrun_type','order_id'
    ];
    public $timestamps=false;
}
