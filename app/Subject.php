<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'dn_id',
        'name',
        'knowledge_area_id',
    ];
}
