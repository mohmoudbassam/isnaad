<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class debit_invoice extends Model
{
    protected $table = 'debit_invoice';
    protected $guarded =[];

    public function account(){
        return $this->belongsTo(store::class,'store_id','account_id');
    }
}
