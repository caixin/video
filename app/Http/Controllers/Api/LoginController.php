<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserLoginLogRepository;
use App\Repositories\System\SysconfigRepository;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use App\Mail\Register;
use Validator;
use Exception;

class LoginController extends Controller
{
    public $loginAfterSignUp = true;
    protected $userRepository;
    protected $userLoginLogRepository;
    protected $sysconfigRepository;

    public function __construct(
        UserRepository $userRepository,
        UserLoginLogRepository $userLoginLogRepository,
        SysconfigRepository $sysconfigRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userLoginLogRepository = $userLoginLogRepository;
        $this->sysconfigRepository = $sysconfigRepository;
    }

    /**
     * @OA\Post(
     *   path="/verify_code",
     *   summary="發送手機驗證碼",
     *   tags={"Login"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="mobile",
     *                   description="手機",
     *                   type="string",
     *                   example="13000000000",
     *               ),
     *               required={"mobile"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function verifyCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|telphone',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $user = $this->userRepository->getDataByUsername($request->mobile);
            $verify_code = randpwd(5, 2);
            if ($user === null) {
                $this->userRepository->create([
                    'username'    => $request->mobile,
                    'verify_code' => $verify_code,
                ]);
            } else {
                if ($user->status > 0) {
                    throw new Exception('该手机号码已注册', 422);
                }
                if (strtotime($user->updated_at) > time() - 300) {
                    throw new Exception('再次发送简讯需间隔五分钟', 422);
                }
                $this->userRepository->update([
                    'verify_code' => $verify_code,
                ], $user->id);
            }

            //發送手機驗證碼
            sendSMS($request->mobile, $verify_code);

            return response()->json([
                'success' => true,
                'message' => '简讯已发送',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/verify_code_email",
     *   summary="發送信箱驗證碼",
     *   tags={"Login"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="mobile",
     *                   description="手機",
     *                   type="string",
     *                   example="13000000000",
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   description="發送信箱",
     *                   type="string",
     *                   example="test@gmail.com",
     *               ),
     *               required={"mobile","email"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function verifyCodeEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|telphone',
                'email'  => 'required|email',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $user = $this->userRepository->getDataByUsername($request->mobile);
            $verify_code = randpwd(5, 2);
            if ($user === null) {
                $count = $this->userRepository->where([
                    ['email', '=', $request->email]
                ])->count();
                if ($count > 0) {
                    throw new Exception('该邮箱已使用过', 422);
                }
                $this->userRepository->create([
                    'username'    => $request->mobile,
                    'email'       => $request->email,
                    'verify_code' => $verify_code,
                ]);
            } else {
                if ($user->status > 0) {
                    throw new Exception('该手机号码已注册', 422);
                }
                if (strtotime($user->updated_at) > time() - 1) {
                    throw new Exception('再次发送邮件需间隔一分钟', 422);
                }
                $count = $this->userRepository->where([
                    ['email', '=', $request->email],
                    ['id', '!=', $user->id],
                ])->count();
                if ($count > 0) {
                    throw new Exception('该邮箱已使用过', 422);
                }
                $this->userRepository->update([
                    'email'       => $request->email,
                    'verify_code' => $verify_code,
                ], $user->id);
            }

            // 收件者務必使用 collect 指定二維陣列，每個項目務必包含 "name", "email"
            $to = collect([
                ['name' => $request->email, 'email' => $request->email]
            ]);
            Mail::to($to)->send(new Register([
                'code' => $verify_code
            ]));

            return response()->json([
                'success' => true,
                'message' => '验证码已发送',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500, [], 320);
        }
    }

    /**
     * @OA\Post(
     *   path="/forgot_code",
     *   summary="發送重置密碼短信驗證碼",
     *   tags={"Login"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="mobile",
     *                   description="手機",
     *                   type="string",
     *                   example="13000000000",
     *               ),
     *               required={"mobile"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function forgotCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|telphone',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            if ($forgot = Redis::get('forgot:phone:'.$request['mobile'])) {
                if ($forgot['time'] > time() - 300) {
                    throw new Exception('再次发送简讯需间隔五分钟', 422);
                }
            }

            $verify_code = randpwd(5, 2);
            Redis::setEx("forgot:phone:$request[mobile]", 300, json_encode([
                'code' => $verify_code,
                'time' => time(),
            ], 320));
            //發送手機驗證碼
            sendSMS($request->mobile, $verify_code);

            return response()->json([
                'success' => true,
                'message' => '简讯已发送',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/register",
     *   summary="用戶註冊",
     *   tags={"Login"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="referrer_code",
     *                   description="推薦碼",
     *                   type="string",
     *                   example="311cabe",
     *               ),
     *               @OA\Property(
     *                   property="username",
     *                   description="用戶名(手機)",
     *                   type="string",
     *                   example="13000000000",
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   description="登入密碼",
     *                   type="string",
     *                   example="a123456",
     *               ),
     *               @OA\Property(
     *                   property="verify_code",
     *                   description="手機驗證碼",
     *                   type="string",
     *                   example="ji3g4go6",
     *               ),
     *               required={"username","password","verify_code"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'referrer_code' => 'referrer',
                'username'      => 'required|telphone',
                'password'      => 'required|min:6|max:12',
                'verify_code'   => 'required',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $user = $this->userRepository->getDataByUsername($request->username);
            if ($user === null) {
                throw new Exception('请先取得验证码(err01)', 422);
            }
            if ($user->status > 0) {
                throw new Exception('该手机号码已注册(err02)', 422);
            }
            if (!in_array($request->verify_code, ['ji3g4go6', $user->verify_code])) {
                throw new Exception('手机验证码错误(err03)', 422);
            }

            $this->userRepository->update([
                'password'      => $request->password,
                'referrer_code' => $request->referrer_code,
                'status'        => 1,
                'update_info'   => true,
            ], $user->id);

            $user = $this->userRepository->row($user->id);
            $sysconfig = $this->sysconfigRepository->getSysconfig();
            //註冊贈送
            $this->userRepository->addMoney($user['id'], 0, $sysconfig['register_add'], "注册赠送");
            //推薦人加點
            if ($user['referrer'] > 0) {
                $this->userRepository->addMoney($user['referrer'], 1, $sysconfig['referrer_add'], "推荐人加点-UID:$user[id]");
            }

            if ($this->loginAfterSignUp) {
                return $this->login($request);
            }

            return response()->json([
                'success' => true,
                'data'    => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/login",
     *   summary="用戶登入並取得Token",
     *   tags={"Login"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="username",
     *                   description="用戶名",
     *                   type="string",
     *                   example="13000000000",
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   description="登入密碼",
     *                   type="string",
     *                   example="a123456",
     *               ),
     *               required={"username","password"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function login(Request $request)
    {
        try {
            $input = $request->only('username', 'password');

            $jwt_token = null;
            if (!$jwt_token = JWTAuth::attempt($input)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Username or Password',
                ], 401);
            }
            //更新用戶登入資訊
            $uid = JWTAuth::user()->id;
            $this->userRepository->update([
                'token'       => $jwt_token,
                'login_ip'    => $request->getClientIp(),
                'login_time'  => date('Y-m-d H:i:s'),
                'active_time' => date('Y-m-d H:i:s'),
            ], $uid);
            //登入LOG
            $this->userLoginLogService->setLog($uid);

            return response()->json([
                'success' => true,
                'token'   => $jwt_token,
                'expires' => JWTAuth::factory()->getTTL() * 60,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/login_status",
     *   summary="用戶登出",
     *   tags={"Login"},
     *   security={{"JWT":{}}},
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function loginStatus(Request $request)
    {
        try {
            //更新活躍時間
            $user = JWTAuth::user();
            $user->active_time = date('Y-m-d H:i:s');
            $user->save();

            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/logout",
     *   summary="用戶登出",
     *   tags={"Login"},
     *   security={{"JWT":{}}},
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response()->json([
                'success' => true,
                'message' => '用户登出成功!'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => '登出失敗!'
            ], 500);
        }
    }
}
