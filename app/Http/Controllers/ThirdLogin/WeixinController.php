<?php

namespace App\Http\Controllers\ThirdLogin;

use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SocialiteProviders\WeixinWeb\Provider;

class WeixinController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        return Socialite::with('weixinweb')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        $user_data = Socialite::with('weixinweb')->stateless()->user();
        dd($user_data);
    }
}
