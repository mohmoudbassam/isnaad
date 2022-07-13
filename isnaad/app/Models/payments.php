<?php

namespace App\Models;

use App\carrier;
use App\statment;
use App\store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class payments extends Model
{
    protected $table='payments';
    protected $primaryKey='id';
    protected $fillable=[
        'payment',	'date',	'invoice_id','type','from_invoice'
    ];


    public function statment(){
        return $this->belongsTo(statment::class, 'invoice_id','id');
    }
    public function statmentDeduct()
    {
        return $this->belongsTo(statment::class,  'from_invoice','inv');
    }



}
