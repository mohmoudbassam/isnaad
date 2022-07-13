<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    protected $table='images';
    protected $primaryKey='id';
    protected $fillable=[
      'real_name','file_name','created_by','type','fk'///0 is damage
    ];




}
