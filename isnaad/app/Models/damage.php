<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class damage extends Model
{
    protected $table='damage';
    protected $primaryKey='id';
    protected $fillable=[
      'sku','shipping_number','cost','paid',
        'transaction_cost','transaction_id',
        'created_by','account_id',
        'order_number','traking_number',
        'date','carrier_id','invo_num'

    ];

    public function image(){
        return $this->hasOne(images::class,'fk','id')->where('type',0);
    }
    public function store(){
        return $this->belongsTo(store::class,'account_id','account_id');
    }  public function carrier(){
        return $this->belongsTo(carrier::class,'carrier_id','id');
    }
    public function skus(){
        return $this->hasMany(damage_sku::class,'damage_id','id');
    }
 protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('testacount', function (Builder $builder) {
            if(auth()->user()->id ==103){
                $builder->where('account_id', '=', 2);
            }

        });
    }


}
