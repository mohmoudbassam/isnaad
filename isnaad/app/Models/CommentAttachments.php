<?php

namespace App\Models;

use App\carrier;
use App\store;
use Illuminate\Database\Eloquent\Model;

class CommentAttachments extends Model
{
    protected $table='comment_attachments';
    protected $primaryKey='id';
    protected $guarded=[];

    public function reply(){
        return $this->belongsTo(CommentReply::class,'comment_reply_id','id');
    }



}
