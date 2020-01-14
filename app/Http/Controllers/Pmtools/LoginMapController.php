<?php

namespace App\Http\Controllers\Pmtools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\UserLoginLogService;

class LoginMapController extends Controller
{
    protected $userLoginLogService;

    public function __construct(UserLoginLogService $userLoginLogService)
    {
        $this->userLoginLogService = $userLoginLogService;
    }

    public function index(Request $request)
    {
        return view('pmtools.login_map', $this->userLoginLogService->loginMap($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'login_map'));
    }
}
