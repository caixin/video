<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['as'=>'web.','middleware'=>['share','frontend']], function () {
    Route::get('/', 'WebController@index')->name('index');
    Route::get('home', 'WebController@index')->name('home');
    Route::get('detail/{keyword}', 'WebController@detail')->name('detail');
    Route::get('search', 'WebController@search')->name('search');
    Route::get('video', 'WebController@video')->name('video');
    Route::get('tags', 'WebController@tags')->name('tags');

    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('auth/weixin', 'ThirdLogin\WeixinController@redirectToProvider');
    Route::get('auth/weixin/callback', 'ThirdLogin\WeixinController@handleProviderCallback');

    Route::post('verify_code', 'Api\LoginController@verifyCode')->name('verify_code');
    Route::post('verify_code_email', 'Api\LoginController@verifyCodeEmail')->name('verify_code_email');
    Route::post('forgot_code', 'Api\LoginController@forgotCode')->name('forgot_code');
    Route::get('register', 'LoginController@register')->name('register');
    Route::post('register', 'LoginController@registerAction');
    Route::get('forgot', 'WebController@forgot')->name('forgot');
    Route::post('forgot', 'WebController@forgotAction');
    Route::get('message', 'WebController@message')->name('message');
    Route::post('message', 'WebController@messageStore')->name('message.store');

    Route::group(['middleware'=>['auth:web']], function () {
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::get('profile', 'WebController@profile')->name('profile');
        Route::post('video/buy', 'WebController@buy')->name('video.buy');
    });
});
