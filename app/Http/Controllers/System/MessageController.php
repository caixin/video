<?php

namespace App\Http\Controllers\System;

use View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\System\AdsForm;
use App\Services\System\MessageService;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        return view('message.index', $this->messageService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'message'));
    }

    public function edit($id)
    {
        View::share('sidebar', false);
        return view('message.edit', $this->messageService->show($id));
    }

    public function update(AdsForm $request, $id)
    {
        $this->messageService->update($request->post(), $id);

        session()->flash('message', '编辑成功!');
        return "<script>parent.window.layer.close();parent.location.reload();</script>";
    }

    public function destroy(Request $request)
    {
        $this->messageService->destroy($request->input('id'));
        session()->flash('message', '删除成功!');
        return 'done';
    }
}
