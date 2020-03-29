<?php

namespace App\Operations;

use App\User;
use GuzzleHttp\Cookie\FileCookieJar;
use Illuminate\Support\Facades\Auth;

/**
 * Class DnCookiesFileOperation
 * @package App\Operations
 */
class DnCookiesFileOperation extends AbstractDnOperation
{
    /**
     * Получить FileCookieJar для текущего пользователя
     *
     * @return FileCookieJar
     */
    public function getMyCookiesFileJar()
    {
        /** @var User $user */
        $user = Auth::user();
        $file = storage_path('app/dn_cookies') . DIRECTORY_SEPARATOR . $user->dn_cookies->dn_cookies_file;
        return new FileCookieJar($file);
    }

    /**
     * Получить FileCookieJar для конкретного пользователя
     *
     * @param User $user
     * @return FileCookieJar
     */
    public function getUserCookiesFileJar(User $user)
    {
        $file = storage_path('app/dn_cookies') . DIRECTORY_SEPARATOR . $user->dn_cookies->dn_cookies_file;
        return new FileCookieJar($file);
    }
}
