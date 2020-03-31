<?php

namespace App\Operations;


use App\DnCookiesFile;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\RedirectMiddleware;
use Illuminate\Support\Facades\Auth;

class ParamsOperation extends AbstractOperation
{
    public function buildArray($assoc_array)
    {
        $params = [];
        foreach ($assoc_array as $key => $item) {
            if (isset($item)) {
                $params[$key] = $item;
            }
        }
        return $params;
    }
}
