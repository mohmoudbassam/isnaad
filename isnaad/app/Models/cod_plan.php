<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class cod_plan extends Model
{
    protected $table='cod_plan';
    protected $primaryKey='id';
    public $timestamps=false;
        protected $fillable=[

            'from_num',
            'to_num',
            'cod',
            'plan_id',


        ];
    public function plan(){
        $this->belongsTo(nstoreplan::class,'plan_id');
    }
}
