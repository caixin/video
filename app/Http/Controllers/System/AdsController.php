<?php

namespace App\Http\Controllers\System;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\AdsForm;
use App\Services\System\AdsService;

class AdsController extends Controller
{
    protected $adsService;

    public function __construct(AdsService $adsService)
    {
        $this->adsService = $adsService;
    }

    public function index(Request $request)
    {
        return view('ads.index', $this->adsService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'ads'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('ads.create', $this->adsService->create($request->input()));
    }

    public function store(AdsForm $request)
    {
        $this->adsService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('ads.edit', $this->adsService->show($id));
    }

    public function update(AdsForm $request, $id)
    {
        $this->adsService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function destroy(Request $request)
    {
        $this->adsService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
