<?php

namespace App\Http\Middleware;

use Route;
use Auth;
use Closure;
use Request;
use Models\Admin\Admin;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //檢測用戶，不可重複登入
        if (session('_token') === null) {
            return $this->errorRedirect('session无效，请重新登录');
        }

        if (session('_token') != Admin::find(session('id'))->token) {
            return $this->errorRedirect("session无效，请重新登录");
        }
        //檢測url是不是存在的
        $allNav = view()->shared('allNav');
        $routes = [];
        foreach ($allNav as $row) {
            if ($row['pid'] > 0) {
                $routes[] = $row['route'];
                if ($row['route1'] != '') {
                    $routes[] = $row['route1'];
                }
                if ($row['route2'] != '') {
                    $routes[] = $row['route2'];
                }
            }
        }

        $routes = array_merge_recursive($routes, config('global.no_need_perm'));
        if (!in_array(Route::currentRouteName(), $routes)) {
            abort(500, '当前的url没有设置,或者已经禁用,请联系管理员设置！');
        }

        //非超级管理员則驗證是否有訪問的權限
        if (session('roleid') != 1 && !in_array(view()->shared('route'), view()->shared('allow_url'))) {
            return $this->errorRedirect("对不起，您没权限执行此操作，请联系管理员");
        }

        return $next($request);
    }

    /**
     * 驗證錯誤 跳轉登入頁
     * $message 跳轉訊息
     *
     * @return null
     */
    private function errorRedirect($message)
    {
        Auth::logout();
        session()->flash('message', $message);
        session('refer', Request::getRequestUri());
        return redirect('login');
    }
}
