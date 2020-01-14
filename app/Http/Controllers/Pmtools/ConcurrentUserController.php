<?php

namespace App\Http\Controllers\Pmtools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Pmtools\ConcurrentUserService;

class ConcurrentUserController extends Controller
{
    protected $concurrentUserService;

    public function __construct(ConcurrentUserService $concurrentUserService)
    {
        $this->concurrentUserService = $concurrentUserService;
    }

    public function index(Request $request)
    {
        return view('pmtools.concurrent_user', $this->concurrentUserService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'ccu'));
    }
}
