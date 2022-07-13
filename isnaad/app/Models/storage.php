<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class storage extends Model
{
    protected $table = 'storage';
    protected $primaryKey = 'id';
    public $timestamps = true;
   protected $appends = ['Storage_type'];
    protected $guarded = [];

    public function store(){
        return $this->belongsTo(store::class,'store_id','account_id');
    }

    public function getStorageTypeAttribute(){
        if($this->type == 1){
            return 'Pallets';
        }else if($this->type == 2){
            return 'Shelf';
        }else if($this->type == 4){
            return 'Special';
        }else{
            return 'cold';
        }
    }

}
