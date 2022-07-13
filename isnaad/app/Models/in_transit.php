<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class in_transit extends Model
{
    protected $table='in_transit';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=[
      'order_id'  
    ];



}
