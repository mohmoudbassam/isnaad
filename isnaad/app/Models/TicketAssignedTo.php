<?php

namespace App\Models;

use App\carrier;
use App\store;

use App\user;
use Illuminate\Database\Eloquent\Model;

class TicketAssignedTo extends Model
{
    protected $table = 'ticket_assigned_to';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }




}
