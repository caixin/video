<?php

use Illuminate\Http\Request;

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

Route::group(['as'=>'api.','middleware'=>['force.json']], function () {
    Route::post('maintenance', 'Api\HomeController@maintenance')->name('maintenance');

    Route::post('param', 'Api\CommonController@param')->name('param');
    Route::post('adslist', 'Api\CommonController@adsList')->name('adslist');

    Route::post('verify_code', 'Api\LoginController@verifyCode')->name('verify_code');
    Route::post('reset_code', 'Api\LoginController@resetCode')->name('reset_code');
    Route::post('register', 'Api\LoginController@register')->name('register');
    Route::post('login', 'Api\LoginController@login')->name('login');

    Route::post('video/list', 'Api\VideoController@list')->name('video.list');
    Route::post('video/tags', 'Api\VideoController@tags')->name('video.tags');
    Route::post('video/detail', 'Api\VideoController@detail')->name('video.detail');

    Route::group(['middleware'=>['jwt.auth']], function () {
        Route::post('logout', 'Api\LoginController@logout')->name('logout');

        Route::post('user/profile', 'Api\UserController@profile')->name('user.profile');
        Route::post('user/referrer', 'Api\UserController@referrer')->name('user.profile');

        Route::post('video/buy', 'Api\VideoController@buy')->name('video.buy');
    });
});
