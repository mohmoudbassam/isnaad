<?php

namespace App\Models;

use App\carrier;
use App\store;
use App\user;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(store::class, 'store_id', 'account_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(TicketType::class, 'status_id');
    }

    public function files()
    {
        return $this->hasMany(TicketFiles::class, 'ticket_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id');
    }

    public function replies()
    {
        return $this->hasManyThrough(CommentReply::class, Comment::class);
    }

    public function assigned()
    {
        return $this->hasMany(TicketAssignedTo::class);
    }

    public function user_assigned()
    {
        return $this->belongsToMany(user::class, 'ticket_assigned_to');

    }

    public function is_closed()
    {
        return $this->status->name == 'closed';
    }

}
