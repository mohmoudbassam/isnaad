<?php

namespace App\Models;

use App\store;
use Illuminate\Database\Eloquent\Model;

class nstoreplan extends Model
{
    protected $table = 'nstoreplan';

    public $timestamps = false;
    protected $fillable = [
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
        'system_fee', 'return_charge', 'return_charge_each_extra', 'extra_wight_gcc', 'allow_wight_sa', 'allow_wight_gcc',
        'Reciving_replanchment', 'return_charge_in', 'return_charge_out', 'fromDate',
        'in_side_ryad_fr',
        'out_side_ryad_fr',
        'cod_charge_fr',
        'each_2nd_units_fr',
        'processing_charge_fr',
        'isnaad_packaging_fr',
        'Reciving_replanchment_fr',
        'system_fee_fr',
        'return_charge_in_fr',
        'return_charge_out_fr',
        'return_charge_each_extra_fr',
        'allowed_weight_in_sa_fr',
        'allow_wight_gcc_fr',
        'add_cost_in_sa_fr',
        'pallet',
        'shelves', 'cold', 'special', 'allowed_weight_in_sa', 'allowed_weight_out_sa',
        'unit_price',
        'add_cost_out_sa', 'GCC',
        'add_cost_in_sa',
        'client_packaging',
        'allow_selves',
        'allow_pallet'

    ];

    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }

    public function cod_plan()
    {
        return $this->hasMany(cod_plan::class, 'plan_id');
    }

}
