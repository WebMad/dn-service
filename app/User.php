<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id',
        'dn_uid',
        'name',
        'email',
        'login',
        'first_name',
        'middle_name',
        'last_name',
        'timezone',
        'birthday',
        'school_id',
        'eg_id',
        'dn_cookies_file_id',
        'dn_access_token',
        'is_auth'
    ];

    public function dn_cookies()
    {
        return $this->hasOne('App\DnCookiesFile', 'id', 'dn_cookies_file_id');
    }
}
