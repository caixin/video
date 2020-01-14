<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminForm;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index(Request $request)
    {
        return view('admin.index', $this->adminService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'admin'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('admin.create', $this->adminService->create($request->input()));
    }

    public function store(AdminForm $request)
    {
        $this->adminService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('admin.edit', $this->adminService->show($id));
    }

    public function update(AdminForm $request, $id)
    {
        $this->adminService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function editpwd()
    {
        View::share('sidebar', false);
        return view('admin.editpwd');
    }

    public function updatepwd(Request $request)
    {
        $this->validate($request, [
            'old_pwd'    => 'required',
            'password'   => 'required',
            'repassword' => 'required|same:password',
        ]);
        $data = $this->adminService->show(session('id'));
        if (! \Hash::check($request->old_pwd, $data['row']['password'])) {
            return back()->withErrors(['old_pwd'=>'旧密码输入错误']);
        }
        
        $this->adminService->update($request->post(), session('id'));
        return "<script>alert('修改完成');parent.window.layer.close();parent.location.reload();</script>";
    }

    public function save(Request $request, $id)
    {
        $this->adminService->save($request->post(), $id);
        return 'done';
    }

    public function destroy(Request $request)
    {
        $this->adminService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
