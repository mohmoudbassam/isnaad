<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class invoice_excel extends Model
{

    protected $table='invoice_excel';
    protected $primaryKey='id';
    protected $fillable=[
      'store_id','path'
    ];


}
