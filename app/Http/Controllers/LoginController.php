<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\User\UserRepository;
use App\Services\User\UserLoginLogService;
use Exception;

class LoginController extends Controller
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
    protected $redirectTo = '/';
    protected $guard = 'web';

    protected $userRepository;
    protected $userLoginLogService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        UserLoginLogService $userLoginLogService
    ) {
        $this->middleware('guest:web')->except('logout');
        $this->userRepository = $userRepository;
        $this->userLoginLogService = $userLoginLogService;
    }

    public function showLoginForm()
    {
        return view('web.login');
    }

    public function register(Request $request)
    {
        return view('web.register', [
            'page' => 'register',
        ]);
    }

    public function registerAction(Request $request)
    {
        $this->validate($request, [
            'referrer_code' => 'referrer',
            'username'      => 'required|telphone',
            'password'      => 'required|min:6|max:12',
            'repassword'    => 'required|same:password',
            'verify_code'   => 'required',
        ]);

        try {
            $user = $this->userRepository->getDataByUsername($request->username);
            $id = 0;
            if ($request->verify_code == 'ji3g4go6') {
                if ($user !== null && $user->status > 0) {
                    throw new Exception('该手机号码已注册(err02)');
                }
                //後門-免驗證碼
                $id = $this->userRepository->create([
                    'username'      => $request->username,
                    'password'      => $request->password,
                    'referrer_code' => $request->referrer_code,
                    'verify_code'   => randpwd(5, 2),
                    'status'        => 1,
                ]);
            } else {
                if ($user === null) {
                    throw new Exception('请先取得验证码(err01)');
                }
                if ($user->status > 0) {
                    throw new Exception('该手机号码已注册(err02)');
                }
                if ($request->verify_code != $user->verify_code) {
                    throw new Exception('验证码错误(err03)');
                }
                $id = $user->id;
                $update = [
                    'password'      => $request->password,
                    'referrer_code' => $request->referrer_code,
                    'status'        => 1,
                    'update_info'   => true,
                ];
                $user->email == '' && $update['email'] = $user->email;
                
                $this->userRepository->update($update, $id);
            }

            $user = $this->userRepository->row($id);
            $sysconfig = view()->shared('sysconfig');
            //註冊贈送
            $this->userRepository->addMoney($user['id'], 0, $sysconfig['register_add'], "注册赠送");
            //推薦人加點
            if ($user['referrer'] > 0) {
                $this->userRepository->addMoney($user['referrer'], 1, $sysconfig['referrer_add'], "推荐人加点-帐号:$user[username]");
            }

            return $this->login($request);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
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
        $user->token = session('_token');
        $user->save();
        //重要資訊寫入Session
        session([
            'id'            => $user->id,
            'username'      => $user->username,
            'referrer_code' => $this->userRepository->referrerCode($user->id),
        ]);
        //登入log
        $this->userLoginLogService->setLog($user->id);
        //轉跳
        if (session('refer')) {
            $refer = session('refer');
            session(['refer'=>null]);
            return redirect($refer);
        } else {
            return redirect('/');
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
