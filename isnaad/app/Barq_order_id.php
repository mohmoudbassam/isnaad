<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barq_order_id extends Model
{
    protected $table='Barq_order_id';
      protected $fillable  = [
        'ship_no',
        'barq_id'
        ];
    public $timestamps=false;
}
