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

    public function subject()
    {
        return $this->hasOne('App\Subject', 'dn_id', 'subject_id');
    }

    public function homework()
    {
        return $this->hasMany('App\Homework', 'lesson_id', 'dn_id');
    }

    public function marks()
    {
        return $this->hasMany('App\Mark', 'lesson_id', 'dn_id');
    }
}
