<?php

namespace App\Http\Controllers\User;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserForm;
use App\Services\User\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        return view('user.index', $this->userService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'user'));
    }

    public function create(Request $request)
    {
        View::share('sidebar', false);
        return view('user.create', $this->userService->create($request->input()));
    }

    public function store(UserForm $request)
    {
        $this->userService->store($request->post());

        session()->flash('message', '添加成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('user.edit', $this->userService->show($id));
    }

    public function update(UserForm $request, $id)
    {
        $this->userService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function money($id)
    {
        View::share('sidebar', false);
        return view('user.money', $this->userService->show($id));
    }

    public function money_update(Request $request, $id)
    {
        $this->validate($request, [
            'money'       => 'required|integer|not_in:0',
            'description' => 'required',
        ], ['money.not_in'=>'增减点数 不可为0']);
        $input = $request->post();
        $input['type'] = $input['money'] < 0 ? 5:4;
        $input['description'] .= '-由'.session('username').'操作';
        $this->userService->addMoney($input, $id);

        session()->flash('message', '人工加减点完成!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function free($id)
    {
        View::share('sidebar', false);
        return view('user.free', $this->userService->show($id));
    }

    public function free_update(Request $request, $id)
    {
        $this->validate($request, [
            'free_day'    => 'integer|min:0'
        ], ['free_day.min'=>'免费天数不可为负数']);

        $this->userService->update([
            'free_time' => date('Y-m-d H:i:s', time() + 86400 * $request->free_day)
        ], $id);

        session()->flash('message', '设定完成!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function save(Request $request, $id)
    {
        $this->userService->save($request->post(), $id);
        return 'done';
    }

    public function destroy(Request $request)
    {
        $this->userService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }

    public function export(Request $request)
    {
        $this->userService->export($request->input());
    }
}
