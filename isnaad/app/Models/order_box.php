<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class order_box extends Model
{
    protected $table = 'order_box';
    protected $primaryKey = 'id';


    protected $fillable = [
        'order_id', 'box_id'
    ];

}
