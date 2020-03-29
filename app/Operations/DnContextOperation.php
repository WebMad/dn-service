<?php

namespace App\Operations;

use Illuminate\Support\Facades\Auth;

class DnContextOperation extends AbstractDnOperation
{
    public function context($dn_access_token)
    {

        return json_decode($this->dn_client->get('users/me/context', [
            'headers' => [
                'Access-Token' => $dn_access_token
            ],
            'form_params' => [
//                'access_token' => $dn_access_token
            ]
        ])->getBody());
    }
}
