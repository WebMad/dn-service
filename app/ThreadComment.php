<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadComment extends Model
{
    protected $fillable = ['dn_id', 'reply_uid', 'author_uid', 'dn_created_at', 'thread_id', 'text'];
}
