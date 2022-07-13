<?php

namespace App\Models;

use App\store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class replenishment extends Model
{
    protected $table='replenishment';
    protected  $fillable=[
      'rep_id','remaining','quantity_recived','date','account_id','quantity_request',
        'last_update'	,	'time'	,'time_type'	,'pallets','rep_type','recieved_befor','is_end'
    ];
    public $timestamps=true;

    public function store(){
        return $this->belongsTo(store::class,'account_id','account_id');
    }

    public function getTimeAttribute($value){
          if($this->last_update==null){
              return 'not compeleted';
          }
        $tt= Carbon::parse($this->date . $value);

        $dd=Carbon::parse($this->last_update);
       return $dd->diffInHours($tt).' H';

    }

           protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('testAcount', function (Builder $builder) {
        
            if(auth()->check()){
              if(auth()->user()->id ==103){
                  $builder->where('account_id', '=', 2);
              }

          }

        });
    }
}
