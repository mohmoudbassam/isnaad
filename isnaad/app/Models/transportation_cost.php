<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class transportation_cost extends Model
{
    protected $table='transportation_cost';
    protected $primaryKey='id';
   protected $guarded=[];

    public function store(){
        return $this->belongsTo(store::class,'store_id','account_id');
    }

}
