<?php

namespace App\Models;

use App\store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoicies extends Model
{

    use SoftDeletes;

    protected $table = 'invoicies';

    protected $fillable = [
        'inv_number',
        'from_date',
        'to_date',
        'store_id',
        'confirmed',
        'excel',
        'pdf',
        'total_before_vat',
        'total_after_vat',
        'total_vat',
        'system',
        'discount',
        'other_expenses',
        'pick_from_clients',
        'system_charge',
        'returns',
        'shipping',
        'handling',
        'storage',
        'receiving',
    ];

    //relations
    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }

    ///scopes
    public function scopeConflict($query, $from_date, $to_date)
    {
        return $query->whereBetween('from_date', [$from_date, $to_date])
            ->orWhereBetween('to_date', [$from_date, $to_date])
            ->orWhereRaw('? BETWEEN from_date and to_date', [$from_date])
            ->orWhereRaw('? BETWEEN from_date and to_date', [$to_date]);
    }

    public function ScopewhenStore($query, $request)
    {

        $query->when($request->store, function ($q) use ($request) {

            return $q->where('store_id', $request->store);
        });
    }

   public function conflictWithClose(){
     return   confirm_invoice::whereHas('draft',function ($q){
            $q->where('store_id',$this->store_id)->Conflict($this->from_date,$this->to_date);
        })->first();
    }
}
