<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class proc_notifcation extends Model
{
    protected $table='proc_notifcation';
    protected $fillable=[
        'order_id','notifcation_count'
    ];
}
