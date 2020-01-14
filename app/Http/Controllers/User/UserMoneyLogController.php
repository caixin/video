<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\UserMoneyLogService;

class UserMoneyLogController extends Controller
{
    protected $userMoneyLogService;

    public function __construct(UserMoneyLogService $userMoneyLogService)
    {
        $this->userMoneyLogService = $userMoneyLogService;
    }

    public function index(Request $request)
    {
        return view('user_money_log.index', $this->userMoneyLogService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'user_money_log'));
    }
}
