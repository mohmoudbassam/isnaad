<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class naqel_city extends Model
{
    protected $table='naqel_city';
    protected $primaryKey='id';
    public $timestamps=false;
      protected $fillable=[
        'CityCode','CityName','StationID','StationCode','CountryCode'
      ];



}
