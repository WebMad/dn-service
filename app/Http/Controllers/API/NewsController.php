<?php

namespace App\Http\Controllers\API;

use App\DnCookiesFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\News;
use App\Operations\DnCookiesFileOperation;
use App\Operations\DnNewsOperation;
use App\User;
use DateTime;
use Defuse\Crypto\File;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index(NewsRequest $request, DnNewsOperation $dnNewsOperation)
    {
        $limit = $request->input(['limit']);
        if (empty($limit)) {
            $limit = 5;
        }

        $user = Auth::user();

        return response()->json($dnNewsOperation->getMixNews($user->school_id, $user->eg_id, $limit));
    }

    public function schoolNews(NewsRequest $request, DnNewsOperation $dnNewsOperation)
    {
        $limit = $request->input(['limit']);
        if (empty($limit)) {
            $limit = 5;
        }

        $user = Auth::user();

        return response()->json($dnNewsOperation->getSchoolNews($user->school_id, $limit));
    }

    public function eduGroupNews(NewsRequest $request, DnNewsOperation $dnNewsOperation)
    {
        $limit = $request->input(['limit']);
        if (empty($limit)) {
            $limit = 5;
        }

        $user = Auth::user();
        return response()->json($dnNewsOperation->getEduGroupNews($user->school_id, $user->eg_id, $limit));
    }
}
