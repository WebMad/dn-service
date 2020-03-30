<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DnFilesHomework extends Model
{
    protected $fillable = [
        'homework_dn_id',
        'file_dn_id',
    ];
    protected $table = 'dn_files_homework';
    public $timestamps = false;
    public $incrementing = false;
}
