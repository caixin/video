<?php

namespace App\Http\Controllers\Pmtools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Pmtools\DailyRetentionService;

class RetentionController extends Controller
{
    protected $dailyRetentionService;

    public function __construct(DailyRetentionService $dailyRetentionService)
    {
        $this->dailyRetentionService = $dailyRetentionService;
    }

    public function index(Request $request)
    {
        return view('pmtools.retention', $this->dailyRetentionService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'retention'));
    }

    public function chart(Request $request)
    {
        return view('pmtools.retention_chart', $this->dailyRetentionService->chart($request->input()));
    }

    public function chartSearch(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'retention_chart'));
    }

    public function analysis(Request $request)
    {
        return view('pmtools.retention_analysis', $this->dailyRetentionService->analysis($request->input()));
    }

    public function analysisSearch(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'retention_analysis'));
    }

    public function user(Request $request)
    {
        return view('pmtools.retention_user', $this->dailyRetentionService->user($request->input()));
    }

    public function userSearch(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'retention_user'));
    }
}
