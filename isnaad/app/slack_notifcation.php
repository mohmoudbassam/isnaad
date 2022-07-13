<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class slack_notifcation extends Model
{
    protected $table='slack_notifcation';
    protected $fillable=[
           'order_id','notifcation_count'
    ];
}
