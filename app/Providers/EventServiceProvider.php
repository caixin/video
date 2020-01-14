<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SocialiteWasCalled::class => [
            'SocialiteProviders\WeixinWeb\WeixinWebExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('tymon.jwt.absent', function () {
            return response()->json([
                'code' => -100,
                'msg'  => '未提供 Token, 请重新登录',
                'data' => '',
            ]);
        });

        Event::listen('tymon.jwt.invalid', function () {
            return response()->json([
                'code' => -100,
                'msg'  => '无效的 Token, 请重新登录',
                'data' => '',
            ]);
        });

        Event::listen('tymon.jwt.expired', function () {
            return response()->json([
                'code' => -100,
                'msg'  => 'Token 已经过期, 请重新登录',
                'data' => '',
            ]);
        });

        Event::listen('tymon.jwt.user_not_found', function () {
            return response()->json([
                'code' => -101,
                'msg'  => '没有找到该用户',
                'data' => '',
            ]);
        });
    }
}
