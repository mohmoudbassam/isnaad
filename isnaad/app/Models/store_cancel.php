<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store_cancel extends Model
{
    protected $table='store_cancel';
    protected $fillable=[
      'store_id','total_order','pages','page_check'
    ];
    public $timestamps=false;


}
