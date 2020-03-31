<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    protected $fillable = [
        'dn_id',
        'school_id',
        'abbreviation',
        'name',
        'is_final',
        'is_important',
        'kind_id',
        'kind',
    ];
}
