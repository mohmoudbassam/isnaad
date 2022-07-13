<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class carrier_city extends Model
{
    public function carrier(){
        return $this->hasMany(carrier::class,'id','carrier_id')->where('active',1);
    }
}
