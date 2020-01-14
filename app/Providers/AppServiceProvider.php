<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UserRepository $userRepository)
    {
        //擴展大陸手機號碼驗證規則
        Validator::extend('telphone', function ($attribute, $value, $parameters) {
            return preg_match('/^(?:\+?86)?1(?:3\d{3}|5[^4\D]\d{2}|8\d{3}|7[^29\D](?(?<=4)(?:0\d|1[0-2]|9\d)|\d{2})|9[189]\d{2}|6[567]\d{2}|4[579]\d{2})\d{6}$/', $value);
        });
        
        //擴展推薦碼驗證規則
        Validator::extend('referrer', function ($attribute, $value, $parameters) use ($userRepository) {
            if ($value != '') {
                return $userRepository->referrerCode($value, 'decode') ? true:false;
            }
            return true;
        });
    }
}
