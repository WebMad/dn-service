<?php

namespace App\Operations;


use App\DnCookiesFile;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\RedirectMiddleware;

class DnAuthOperation extends AbstractDnOperation
{

    /**
     * Получить адрес cookie файла для взаимодействия с дневник.ру
     *
     * @param $cookie_id
     * @return string
     */
    public function getCookieFile($cookie_id)
    {
        return storage_path('app/dn_cookies') . '/cookie_' . $cookie_id . '.txt';
    }

    /**
     * Авторизует пользователя через дневник.ру
     *
     * @param $login
     * @param $password
     * @param $vk_user_id
     * @return array
     */
    public function login($login, $password)
    {
        $fields = [
            'login' => $login,
            'password' => $password,
            'exceededAttempts' => false,
            "ReturnUrl" => "https://dnevnik.ru/user/settings.aspx",
        ];

        $cookies_model = DnCookiesFile::create([]);
        $cookie_file_name = 'cookies_' . $cookies_model->id . '.txt';
        $cookies_model->dn_cookies_file = $cookie_file_name;
        $cookies_model->save();

        $cookie_file_jar = new FileCookieJar(storage_path('app/dn_cookies') . DIRECTORY_SEPARATOR . $cookie_file_name);
        $client = new Client([
            'base_uri' => 'https://dnevnik.ru/api/',
            'cookies' => $cookie_file_jar,
        ]);

        $result_http = $client->post('https://login.dnevnik.ru/login/esia/astrakhan', [
            'form_params' => $fields
        ]);

        $result_content = $result_http->getBody()->getContents();

        if (stripos($result_content, 'Войти в Дневник.ру')) {
            return [
                'result' => false,
            ];
        }
        $start_str = mb_substr($result_content, mb_stripos($result_content, 'https://dnevnik.ru/user/settings.aspx?user=') + 43);
        $user_id = mb_substr($start_str, 0, mb_stripos($start_str, '"'));
        return [
            'result' => true,
            'user_id' => $user_id,
            'cookie_jar' => $cookie_file_jar,
            'cookie_model' => $cookies_model
        ];
    }

    /**
     * Получить токен для работы с api дневник.ру
     *
     * @param CookieJar $cookie_jar
     * @return false|string
     */
    public function getDnevnikAccessToken($cookie_jar)
    {

        $client = new Client([
            'base_uri' => 'https://login.dnevnik.ru/oauth2/'
        ]);
        $access_token_http = $client->post('', [
            'allow_redirects' => [
                'track_redirects' => true
            ],
            'form_params' => [
                'access_token' => 0,
                'response_type' => 'token',
                'client_id' => env('DNEVNIK_CLIENT_ID'),
                'scope' => 'Avatar,FullName,Schools,EduGroups,Lessons,Marks,Relatives,Roles,EmailAddress,Birthday,Messages',
                'is_granted' => 'true'
            ],
            'cookies' => $cookie_jar
        ]);
        return explode('=', explode('&', $access_token_http->getHeader(RedirectMiddleware::HISTORY_HEADER)[0])[4])[2];
    }

}
