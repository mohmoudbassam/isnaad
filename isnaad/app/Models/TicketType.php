<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $table='ticket_type';
    protected $primaryKey='id';
    public $timestamps=false;



}
