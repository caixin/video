<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Admin\AdminLoginLogRepository;
use App\Repositories\System\Ip2locationRepository;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * 登入後導向的位置
     *
     * @var string
     */
    protected $redirectTo = 'home';
    protected $guard = 'backend';

    protected $adminLoginLogRepository;
    protected $ip2locationRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AdminLoginLogRepository $adminLoginLogRepository,
        Ip2locationRepository $ip2locationRepository
    ) {
        $this->middleware('guest:backend')->except('logout');
        $this->adminLoginLogRepository = $adminLoginLogRepository;
        $this->ip2locationRepository = $ip2locationRepository;
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * 通過驗證後的動作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //更新登入數次及時間
        $user->login_time = date('Y-m-d H:i:s');
        $user->login_count++;
        $user->token = session('_token');
        $user->save();
        //重要資訊寫入Session
        session([
            'id'       => $user->id,
            'username' => $user->username,
            'roleid'   => $user->roleid,
            'per_page' => 20,
        ]);
        //登入log
        $ip = $request->getClientIp();
        $ip_info = $this->ip2locationRepository->getIpData($ip);
        $ip_info = $ip_info ?? [];
        $this->adminLoginLogRepository->create([
            'adminid' => $user->id,
            'ip'      => $ip,
            'ip_info' => json_encode($ip_info),
        ]);
        //轉跳
        if (session('refer')) {
            $refer = session('refer');
            session(['refer'=>null]);
            return redirect($refer);
        } else {
            return redirect('home');
        }
    }

    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * 定義帳號欄位
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 登出後動作
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect('login');
    }
}
