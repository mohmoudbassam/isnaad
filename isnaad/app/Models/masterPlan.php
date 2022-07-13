<?php

namespace App\Models;

use App\carrier;
use App\order;
use App\store;
use Illuminate\Database\Eloquent\Model;

class masterPlan extends Model
{
    protected $table = 'masterplan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'from_date', 'store_id', 'each_2nd_units', 'isnaad_packaging', 'processing_charge', 'cod_charge',
        'extra_wight_ksa', 'in_side_ryad', 'out_side_ryad', 'return_charge_in', 'return_charge_out',
        'GCC', 'system_fee', 'return_charge', 'return_charge_each_extra', 'extra_wight_gcc', 'allow_wight_sa', 'allow_wight_gcc',
        'Reciving_replanchment', 'created_at', 'updated_at', 'added_by', 'updated_by', 'allow_wight_gcc',
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
        'allowed_weight_in_sa',
        'allowed_weight_out_sa',
        'allow_wight_gcc_fr',
        'add_cost_in_sa_fr',
        'add_cost_in_sa',
        'GCC','GCC_fr',
        'add_cost_out_sa',
         'shelves',
        'pallet',
        'cold',
        'special',
         'unit_price',
        'client_packaging',
        ''

    ];

    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }

}
