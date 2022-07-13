<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\store;
class order_cancel extends Model
{
    protected $table='order_cancel';
    protected $fillable=[
      'order_number',	'f_name',	'city',	'cancel_date',	'account_id'
    ];
    public $timestamps=false;
 public function store(){
        return $this->belongsTo(store::class,'account_id','account_id');
    }

}
