<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class daliay extends Model
{
    use SoftDeletes;
    protected $table='daliay';
    protected $fillable=['real_name','storage_name','user_id'];
    public function user(){
        return $this->belongsTo(user::class,'user_id','id');
    }

}
