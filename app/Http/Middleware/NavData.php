<?php

namespace App\Http\Middleware;

use View;
use Route;
use Closure;
use Models\Admin\AdminRole;
use App\Repositories\Admin\AdminNavRepository;

class NavData
{
    protected $adminNavRepository;

    public function __construct(AdminNavRepository $adminNavRepository)
    {
        $this->adminNavRepository = $adminNavRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //取得當前Route
        $route = Route::currentRouteName();
        $share['route'] = $route;
        $action = explode('.', $route);
        $share['controller'] = "$action[0]";
        //導航列表
        $nav = $this->adminNavRepository->allNav();
        $share['allNav'] = $nav;
        //導航權限
        $role = AdminRole::find(session('roleid'))->toArray();
        $permition = $role !== null && $role['allow_nav'] != '' ? json_decode($role['allow_nav'], true) : [];
        $routes = [];
        foreach ($nav as $row) {
            if ($row['pid'] > 0) {
                $routes[$row['route']] = $row;
                if ($row['route1'] != '') {
                    $routes[$row['route1']] = $row;
                }
                if ($row['route2'] != '') {
                    $routes[$row['route2']] = $row;
                }
            }
            //子路由寫入權限
            if (in_array($row['route'], $permition)) {
                if ($row['route1'] != '') {
                    $permition[] = $row['route1'];
                }
                if ($row['route2'] != '') {
                    $permition[] = $row['route2'];
                }
            }
        }
        $permition = array_merge_recursive($permition, config('global.no_need_perm'));
        $share['allow_url'] = array_unique($permition);
        //導航路徑
        $navid = isset($routes[$route]) ? $routes[$route]['id'] : 0;
        $share['breadcrumb'] = $this->getBreadcrumb($nav, $navid);
        //內頁Title
        $share['title'] = isset($routes[$route]) ? $routes[$route]['name'] : '首页';
        //導航樹狀
        $share['navList'] = $this->treeNav($nav);

        View::share($share);
        return $next($request);
    }

    /**
     * 遞迴整理導航樹狀結構
     *
     * @param array $result 導航清單
     * @param integer $pid 上層導航ID
     * @return array
     */
    private function treeNav($result, $pid = 0)
    {
        $data = [];
        foreach ($result as $row) {
            if ($row['pid'] == $pid) {
                $row['sub'] = $this->treeNav($result, $row['id']);
                $row['subNavs'] = array_column($row['sub'], 'route');
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * 遞迴取得導航路徑
     *
     * @param array $result 導航清單
     * @param integer $id 導航ID
     * @return array
     */
    private function getBreadcrumb($result, $id)
    {
        if ($id == 0) {
            return [];
        }

        $data = $this->getBreadcrumb($result, $result[$id]['pid']);
        $data[] = $result[$id];
        return $data;
    }
}
