<?php

namespace App\Models;

use App\carrier;
use App\Models\invoicies;
use App\store;
use Illuminate\Database\Eloquent\Model;
use App\statment;
class confirm_invoice extends Model
{
    protected $table = 'confirm_invoice';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function draft()
    {
      return $this->belongsTo(invoicies::class,'draf_id','id');
    }
    public function ScopewhenStore($query, $request)
    {

        $query->when($request->store, function ($q)use($request) {

            return $q->whereHas('draft', function($q2)use($request){
                $q2->where('store_id',$request->store);
            });
        });
    }
     public function Billing(){
        return $this->hasOne(statment::class,'confirmed_id');
    }

}
