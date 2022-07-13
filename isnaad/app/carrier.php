<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class carrier extends Model
{
     protected $table='carriers';

  public function orders(){
        return $this->hasMany(order::class,'carrier','name');
  }
}
