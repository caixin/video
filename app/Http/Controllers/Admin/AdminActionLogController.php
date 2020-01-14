<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AdminActionLogService;

class AdminActionLogController extends Controller
{
    protected $adminActionLogService;

    public function __construct(AdminActionLogService $adminActionLogService)
    {
        $this->adminActionLogService = $adminActionLogService;
    }

    public function index(Request $request)
    {
        return view('admin_action_log.index', $this->adminActionLogService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'admin_action_log'));
    }
}
