<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'dn_id',
        'title',
        'date',
        'number',
        'subject_id',
        'status',
        'result_place_id',
        'building',
        'place',
        'floor',
        'hours',
    ];
}
