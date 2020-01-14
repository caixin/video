<?php

namespace App\Http\Controllers\System;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\SysconfigForm;
use App\Services\System\SysconfigService;

class SysconfigController extends Controller
{
    protected $sysconfigService;

    public function __construct(SysconfigService $sysconfigService)
    {
        $this->sysconfigService = $sysconfigService;
    }

    public function index(Request $request)
    {
        return view('sysconfig.index', $this->sysconfigService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'sysconfig'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('sysconfig.create', $this->sysconfigService->create($request->input()));
    }

    public function store(SysconfigForm $request)
    {
        $this->sysconfigService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function update(Request $request, $id)
    {
        $this->sysconfigService->update($request->post());

        session()->flash('message', '编辑成功!');
        return redirect(route('sysconfig.index', ['groupid'=>$id]));
    }

    public function destroy(Request $request)
    {
        $this->sysconfigService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
