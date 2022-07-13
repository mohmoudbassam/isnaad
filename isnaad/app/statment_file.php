<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class statment_file extends Model
{
    protected $table='statment_file';
     public $timestamps=false;
     protected $fillable=[
         'statment_id','store_name','real_name'
     ];

}
