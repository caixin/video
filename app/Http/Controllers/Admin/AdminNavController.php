<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminNavForm;
use App\Services\Admin\AdminNavService;

class AdminNavController extends Controller
{
    protected $adminNavService;

    public function __construct(AdminNavService $adminNavService)
    {
        $this->adminNavService = $adminNavService;
    }

    public function index(Request $request)
    {
        return view('admin_nav.index', $this->adminNavService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'admin_nav'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('admin_nav.create', $this->adminNavService->create($request->input()));
    }

    public function store(AdminNavForm $request)
    {
        $this->adminNavService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('admin_nav.edit', $this->adminNavService->show($id));
    }

    public function update(AdminNavForm $request, $id)
    {
        $this->adminNavService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function save(Request $request, $id)
    {
        $this->adminNavService->save($request->post(), $id);
        return 'done';
    }

    public function destroy(Request $request)
    {
        $this->adminNavService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
