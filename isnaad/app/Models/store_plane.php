<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class store_plane extends Model
{
    protected $table='store_plane';

    public $timestamps=false;
            protected $fillable=[
        'store_id',
        'to_num',
        'from_num',
        'each_2nd_units',
        'isnaad_packaging',
        'processing_charge',
        'cod',
        'extra_wight_ksa',
        'in_side_ryad',
        'out_side_ryad',
        'GCC',
        'system_fee','return_charge','return_charge_each_extra','extra_wight_gcc','allow_wight_sa','allow_wight_gcc',
        'Reciving_replanchment','return_charge_in','return_charge_out'

    ];

}
