<?php

namespace App\Operations;

class DnUserOperation extends AbstractDnOperation
{
    public function me($dn_access_token)
    {
        return json_decode($this->dn_client->get('users/me', [
            'headers' => [
                'Access-Token' => $dn_access_token
            ],
            'form_params' => [
//                'access_token' => $dn_access_token
            ]
        ])->getBody());
    }

    public function getUser($dn_access_token, $user_id)
    {
        return json_decode($this->dn_client->get('users/' . $user_id, [
            'headers' => [
                'Access-Token' => $dn_access_token
            ],
            'form_params' => [
//                'access_token' => $dn_access_token
            ]
        ])->getBody());
    }
}
