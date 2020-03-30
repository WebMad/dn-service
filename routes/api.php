<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api'], 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'news'], function () {
        Route::get('/', 'NewsController@index');
        Route::get('/school', 'NewsController@schoolNews');
        Route::get('/edu-group', 'NewsController@eduGroupNews');
    });
    Route::get('homework', 'HomeworkController@homework');
    Route::post('logout', 'AuthController@logout');
});
Route::get('login', 'API\AuthController@needLogin')->name('need_login');
Route::post('login', 'API\AuthController@login');
