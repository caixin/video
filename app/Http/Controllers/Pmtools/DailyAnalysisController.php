<?php

namespace App\Http\Controllers\Pmtools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Pmtools\DailyAnalysisService;

class DailyAnalysisController extends Controller
{
    protected $dailyAnalysisService;

    public function __construct(DailyAnalysisService $dailyAnalysisService)
    {
        $this->dailyAnalysisService = $dailyAnalysisService;
    }

    public function index(Request $request)
    {
        return view('pmtools.daily_analysis', $this->dailyAnalysisService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'analysis'));
    }
}
