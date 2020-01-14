<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use Validator;
use Exception;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *   path="/user/profile",
     *   summary="用戶資訊",
     *   tags={"User"},
     *   security={{"JWT":{}}},
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function profile(Request $request)
    {
        try {
            $user = JWTAuth::user();

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'         => $user->id,
                    'username'   => $user->username,
                    'money'      => $user->money,
                    'status'     => $user->status,
                    'created_at' => date('Y-m-d H:i:s', strtotime($user->created_at)),
                ],
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
     *   path="/user/referrer",
     *   summary="推薦人列表",
     *   tags={"User"},
     *   security={{"JWT":{}}},
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function referrer(Request $request)
    {
        try {
            $user = JWTAuth::user();

            $result = $this->userRepository->getReferrerList($user->id);
            $list = [];
            foreach ($result as $row) {
                $list[] = [
                    'username'    => $row->username,
                    'money'       => $row->money,
                    'status'      => $row->status,
                    'login_time'  => $row->login_time,
                    'created_at' => date('Y-m-d H:i:s', strtotime($row->created_at)),
                ];
            }

            return response()->json([
                'success' => true,
                'data'    => $list,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
