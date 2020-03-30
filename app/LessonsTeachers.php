<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonsTeachers extends Model
{
    public $incrementing = false;
    protected $fillable = ['lesson_dn_id', 'teacher_dn_id'];
    public $timestamps = false;
}
