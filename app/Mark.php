<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = [
        'dn_id',
        'dn_str_id',
        'type',
        'value',
        'textValue',
        'person_id',
        'person_str_id',
        'homework_id',
        'homework_str_id',
        'lesson_id',
        'lesson_str_id',
        'number',
        'date',
        'work_type',
        'mood',
        'use_avg_calc',
    ];
}
