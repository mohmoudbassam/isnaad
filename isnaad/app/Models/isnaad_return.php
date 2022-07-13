<?php

namespace App\Models;

use App\carrier;
use App\statment;
use App\store;
use Illuminate\Database\Eloquent\Model;
use App\order;
class isnaad_return extends Model
{
    protected $table = 'isnaad_return';
    protected $primaryKey = 'id';
    protected $fillable = [
        'account_id',
        'traking_number',
        'waybill_url',
        'order_id',
        'carrier_id',
        'shipping_number',
        'status',///1 => deliverd
        'delivred_date',
        'inv_num'
    ];

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

