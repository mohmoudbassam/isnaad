<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class box extends Model
{
    protected $table = 'box';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

}
