<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class carrire_shipment extends Model
{
    protected $table='carrire_shipment';
    protected $primaryKey='id';
    public $timestamps=false;
    public function scopeInRiyadh($query)
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $query->where('place','0');
    }
     public function scopeOutRiyadh($query)
    {
        // dd(Carbon::now()->format('Y-m-d'));
        return $query->where('place','1');
    }


}
