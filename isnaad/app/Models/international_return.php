<?php

namespace App\Models;

use App\carrier;
use App\order;
use App\store;
use Illuminate\Database\Eloquent\Model;

class international_return extends Model
{
    protected $table='international_return';
    protected $primaryKey='id';
    protected $guarded=[];

    public function order(){
        return $this->belongsTo(order::class,'order_id','id');
    }
    public function carrier(){
        return $this->belongsTo(carrier::class,'carrier_id','id');
    }
    public function store()
    {
        return $this->belongsTo( store::class,'account_id','account_id');
    }
}
