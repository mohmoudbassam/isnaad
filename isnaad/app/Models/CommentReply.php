<?php

namespace App\Models;

use App\carrier;
use App\store;
use App\user;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    protected $table = 'comments_reply';
    protected $primaryKey = 'id';
    protected $guarded = [];


    public function sender(){
        return $this->belongsTo(user::class,'send_by');
    }

    public function attachment(){
        return $this->hasOne(CommentAttachments::class,'comment_reply_id','id');
    }

}
