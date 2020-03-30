<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DnFiles extends Model
{
    protected $fillable = [
        'dn_id',
        'dn_str_id',
        'name',
        'type_group',
        'type',
        'page_url',
        'download_url',
        'size',
        'uploaded_date',
        'storage_type',
        'owner_dn_uid',
    ];
}
