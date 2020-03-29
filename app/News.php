<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['text', 'dn_news_id', 'topic_name', 'views_count', 'author_uid', 'dn_created_at', 'school_id', 'eg_id', 'thread_id'];
}
