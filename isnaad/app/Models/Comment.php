<?php

namespace App\Models;

use App\user;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function created_by()
    {
        return $this->belongsTo(user::class, 'created_by');
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class, 'comment_id');
    }
}
