<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\UserLoginLogService;

class UserLoginLogController extends Controller
{
    protected $userLoginLogService;

    public function __construct(UserLoginLogService $userLoginLogService)
    {
        $this->userLoginLogService = $userLoginLogService;
    }

    public function index(Request $request)
    {
        return view('user_login_log.index', $this->userLoginLogService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'user_login_log'));
    }
}
