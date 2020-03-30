<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $fillable = [
        'dn_id',
        'dn_str_id',
        'type',
        'work_type',
        'mark_type',
        'mark_count',
        'lesson_id',
        'lesson_str_id',
        'display_in_journal',
        'status',
        'eg_id',
        'eg_str_id',
        'text',
        'period_number',
        'period_type',
        'subject_dn_id',
        'is_important',
        'target_date',
        'sent_date',
        'created_by',
    ];

    protected $table = 'homework';

    public function lesson()
    {
        return $this->hasOne('App\Lesson', 'dn_id', 'lesson_id');
    }

    public function subject()
    {
        return $this->hasOne('App\Subject', 'dn_id', 'subject_dn_id');
    }

    public function files()
    {
        return $this->belongsToMany('App\DnFiles', 'dn_files_homework', 'homework_dn_id', 'file_dn_id', 'dn_id', 'dn_id');
    }
}
