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

// Authentication Routes...
Route::get('login', 'Admin\AdminLoginController@showLoginForm')->name('login');
Route::post('login', 'Admin\AdminLoginController@login')->name('loginaction');
//AJAX
Route::post('ajax/topinfo', 'AjaxController@getTopInfo')->name('ajax.topinfo');
Route::post('ajax/perpage', 'AjaxController@setPerPage')->name('ajax.perpage');
Route::post('ajax/imageupload/{dir}', 'AjaxController@imageUpload')->name('ajax.imageupload');

Route::group(['middleware'=>['auth:backend','navdata','permission','share']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('logout', 'Admin\AdminLoginController@logout')->name('logout');

    Route::resource('admin', 'Admin\AdminController', ['only'=>['index','create','store','edit','update','destroy']]);
    Route::post('admin/search', 'Admin\AdminController@search')->name('admin.search');
    Route::post('admin/{admin}/save', 'Admin\AdminController@save')->name('admin.save');
    Route::get('admin/editpwd', 'Admin\AdminController@editpwd')->name('admin.editpwd');
    Route::post('admin/updatepwd', 'Admin\AdminController@updatepwd')->name('admin.updatepwd');

    Route::resource('admin_role', 'Admin\AdminRoleController', ['only'=>['index','create','store','edit','update','destroy']]);
    Route::post('admin_role/search', 'Admin\AdminRoleController@search')->name('admin_role.search');
    Route::post('admin_role/{admin}/save', 'Admin\AdminRoleController@save')->name('admin_role.save');

    Route::resource('admin_nav', 'Admin\AdminNavController', ['only'=>['index','create','store','edit','update','destroy']]);
    Route::post('admin_nav/search', 'Admin\AdminNavController@search')->name('admin_nav.search');
    Route::post('admin_nav/{admin_nav}/save', 'Admin\AdminNavController@save')->name('admin_nav.save');

    Route::get('admin_login_log', 'Admin\AdminLoginLogController@index')->name('admin_login_log.index');
    Route::post('admin_login_log/search', 'Admin\AdminLoginLogController@search')->name('admin_login_log.search');

    Route::get('admin_action_log', 'Admin\AdminActionLogController@index')->name('admin_action_log.index');
    Route::post('admin_action_log/search', 'Admin\AdminActionLogController@search')->name('admin_action_log.search');

    Route::resource('user', 'User\UserController', ['only'=>['index','create','store','edit','update','destroy']]);
    Route::post('user/search', 'User\UserController@search')->name('user.search');
    Route::post('user/{user}/save', 'User\UserController@save')->name('user.save');
    Route::get('user/export', 'User\UserController@export')->name('user.export');
    Route::get('user/{user}/money', 'User\UserController@money')->name('user.money');
    Route::put('user/{user}/money', 'User\UserController@money_update')->name('user.money_update');
    Route::get('user/{user}/free', 'User\UserController@free')->name('user.free');
    Route::put('user/{user}/free', 'User\UserController@free_update')->name('user.free_update');

    Route::get('user_login_log', 'User\UserLoginLogController@index')->name('user_login_log.index');
    Route::post('user_login_log/search', 'User\UserLoginLogController@search')->name('user_login_log.search');

    Route::get('user_money_log', 'User\UserMoneyLogController@index')->name('user_money_log.index');
    Route::post('user_money_log/search', 'User\UserMoneyLogController@search')->name('user_money_log.search');

    Route::get('video', 'Video\VideoController@index')->name('video.index');
    Route::post('video/search', 'Video\VideoController@search')->name('video.search');

    Route::get('video_actors', 'Video\VideoActorsController@index')->name('video_actors.index');
    Route::post('video_actors/search', 'Video\VideoActorsController@search')->name('video_actors.search');
    Route::post('video_actors/{video_actors}/save', 'Video\VideoActorsController@save')->name('video_actors.save');

    Route::get('video_tags', 'Video\VideoTagsController@index')->name('video_tags.index');
    Route::post('video_tags/search', 'Video\VideoTagsController@search')->name('video_tags.search');
    Route::post('video_tags/{video_tags}/save', 'Video\VideoTagsController@save')->name('video_tags.save');

    Route::resource('sysconfig', 'System\SysconfigController', ['only'=>['index','create','store','update','destroy']]);
    Route::post('sysconfig/search', 'System\SysconfigController@search')->name('sysconfig.search');

    Route::resource('ads', 'System\AdsController', ['only'=>['index','create','store','edit','update','destroy']]);
    Route::post('ads/search', 'System\AdsController@search')->name('ads.search');

    Route::resource('domain_setting', 'System\DomainSettingController', ['only'=>['index','create','store','edit','update','destroy']]);
    Route::post('domain_setting/search', 'System\DomainSettingController@search')->name('domain_setting.search');

    Route::get('ccu', 'Pmtools\ConcurrentUserController@index')->name('ccu.index');
    Route::post('ccu/search', 'Pmtools\ConcurrentUserController@search')->name('ccu.search');

    Route::get('analysis', 'Pmtools\DailyAnalysisController@index')->name('analysis.index');
    Route::post('analysis/search', 'Pmtools\DailyAnalysisController@search')->name('analysis.search');

    Route::get('daily_user', 'Pmtools\DailyUserController@index')->name('daily_user.index');
    Route::post('daily_user/search', 'Pmtools\DailyUserController@search')->name('daily_user.search');

    Route::get('retention', 'Pmtools\RetentionController@index')->name('retention.index');
    Route::post('retention/search', 'Pmtools\RetentionController@search')->name('retention.search');
    Route::get('retention_chart', 'Pmtools\RetentionController@chart')->name('retention_chart.index');
    Route::post('retention_chart/search', 'Pmtools\RetentionController@chartSearch')->name('retention_chart.search');
    Route::get('retention_analysis', 'Pmtools\RetentionController@analysis')->name('retention_analysis.index');
    Route::post('retention_analysis/search', 'Pmtools\RetentionController@analysisSearch')->name('retention_analysis.search');
    Route::get('retention_user', 'Pmtools\RetentionController@user')->name('retention_user.index');
    Route::post('retention_user/search', 'Pmtools\RetentionController@userSearch')->name('retention_user.search');

    Route::get('login_map', 'Pmtools\LoginMapController@index')->name('login_map.index');
    Route::post('login_map/search', 'Pmtools\LoginMapController@search')->name('login_map.search');

    Route::resource('message', 'System\MessageController', ['only'=>['index','edit','update','destroy']]);
    Route::post('message/search', 'System\MessageController@search')->name('message.search');
});
