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

    public function getPathToCookies($cookie_file_name)
    {
        return storage_path('app/dn_cookies') . DIRECTORY_SEPARATOR . $cookie_file_name;
    }

    /**
     * Получить FileCookieJar для текущего пользователя
     *
     * @return FileCookieJar
     */
    public function getMyCookiesFileJar()
    {
        /** @var User $user */
        $user = Auth::user();
        $file = $this->getPathToCookies($user->dn_cookies->dn_cookies_file);
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
        $file = $this->getPathToCookies($user->dn_cookies->dn_cookies_file);
        return new FileCookieJar($file);
    }
}
