<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Operations\DnAuthOperation;
use App\Operations\DnContextOperation;
use App\Operations\DnUserOperation;
use App\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(DnAuthOperation $dnAuthOperation, DnUserOperation $dnUserOperation, DnContextOperation $dnContextOperation)
    {
        $credentials = request(['login', 'password']);
        if (!empty($credentials['login']) && !empty($credentials['password'])) {
            $auth_request = $dnAuthOperation->login($credentials['login'], $credentials['password']);
            if (!$auth_request['result']) {
                return response()->json([
                    'result' => false,
                    'msg' => 'Неверный логин или пароль'
                ], 401);
            }
            $dn_access_token = $dnAuthOperation->getDnevnikAccessToken($auth_request['cookie_jar']);
            $context = $dnContextOperation->context($dn_access_token);
            $user = $dnUserOperation->getUser($dn_access_token, $context->userId);

            $user_db = User::where([
                'dn_uid' => $user->id,
            ])->first();

            if (empty($user_db)) {
                $user_db = User::create([
                    'person_id' => $user->personId,
                    'dn_uid' => $user->id,
                    'email' => $user->email,
                    'login' => $user->login,
                    'short_name' => $user->shortName,
                    'timezone' => $user->timezone,
                    'birthday' => $user->birthday,
                    'school_id' => (!empty($context->schools[0])) ? $context->schools[0]->id : null,
                    'eg_id' => (!empty($context->eduGroups[0])) ? $context->eduGroups[0]->id : null,
                    'dn_cookies_file_id' => $auth_request['cookie_model']->id,
                    'dn_access_token' => $dn_access_token,
                ]);
            } else {
                $user_db->dn_cookies_file_id = $auth_request['cookie_model']->id;
                $user_db->dn_access_token = $dn_access_token;
                $user_db->save();
            }

            $token = $user_db->createToken('Personal access token');

            $token->token->expires_at = Carbon::now()->addMonth();

            $token->token->save();

            return response()->json([
                'result' => true,
                'msg' => 'Вход выполнен',
                'access_token' => $token->accessToken,
                'token_type' => 'bearer',
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
            ]);
        } else {
            return response()->json([
                'result' => false,
                'msg' => 'Не все поля заполнены'
            ]);
        }
    }
}
