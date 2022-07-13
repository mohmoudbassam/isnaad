<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class zajil_request extends Model
{
    protected $table='zajil_request';
    protected $fillable=[
      'order_id',
    ];
    public $timestamps=false;


}
