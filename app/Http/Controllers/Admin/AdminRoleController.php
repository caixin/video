<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminRoleForm;
use App\Services\Admin\AdminRoleService;

class AdminRoleController extends Controller
{
    protected $adminRoleService;

    public function __construct(AdminRoleService $adminRoleService)
    {
        $this->adminRoleService = $adminRoleService;
    }

    public function index(Request $request)
    {
        return view('admin_role.index', $this->adminRoleService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'admin'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('admin_role.create', $this->adminRoleService->create($request->input()));
    }

    public function store(AdminRoleForm $request)
    {
        $this->adminRoleService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('admin_role.edit', $this->adminRoleService->show($id));
    }

    public function update(AdminRoleForm $request, $id)
    {
        $this->adminRoleService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function save(Request $request, $id)
    {
        $this->adminRoleService->save($request->post(), $id);
        return 'done';
    }

    public function destroy(Request $request)
    {
        $this->adminRoleService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
