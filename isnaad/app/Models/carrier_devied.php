<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class carrier_devied extends Model
{
    protected $table='carrier_devied';
    protected $primaryKey='id';
    public $timestamps=false;


    public function carrier(){
        return $this->belongsTo(carrier::class,'carrier_id','id');
    }

}
