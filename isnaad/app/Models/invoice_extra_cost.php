<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class invoice_extra_cost extends Model
{
    protected $table = 'invoice_extra_cost';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }


  public function ScopewhenStore($query, $request)
    {

        $query->when($request->store, function ($q)use($request) {

            return $q->where('store_id', $request->store);
        });
    }
}
