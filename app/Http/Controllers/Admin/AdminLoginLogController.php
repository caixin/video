<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AdminLoginLogService;

class AdminLoginLogController extends Controller
{
    protected $adminLoginLogService;

    public function __construct(AdminLoginLogService $adminLoginLogService)
    {
        $this->adminLoginLogService = $adminLoginLogService;
    }

    public function index(Request $request)
    {
        return view('admin_login_log.index', $this->adminLoginLogService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'admin_login_log'));
    }
}
