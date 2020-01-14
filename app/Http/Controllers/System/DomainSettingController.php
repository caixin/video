<?php

namespace App\Http\Controllers\System;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\DomainSettingForm;
use App\Services\System\DomainSettingService;

class DomainSettingController extends Controller
{
    protected $domainSettingService;

    public function __construct(DomainSettingService $domainSettingService)
    {
        $this->domainSettingService = $domainSettingService;
    }

    public function index(Request $request)
    {
        return view('domain_setting.index', $this->domainSettingService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'domain_setting'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('domain_setting.create', $this->domainSettingService->create($request->input()));
    }

    public function store(DomainSettingForm $request)
    {
        $this->domainSettingService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('domain_setting.edit', $this->domainSettingService->show($id));
    }

    public function update(DomainSettingForm $request, $id)
    {
        $this->domainSettingService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function destroy(Request $request)
    {
        $this->domainSettingService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
