<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use View;
use App\Repositories\System\DomainSettingRepository;

class Frontend
{
    protected $domainSettingRepository;

    public function __construct(DomainSettingRepository $domainSettingRepository)
    {
        $this->domainSettingRepository = $domainSettingRepository;
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
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $user->active_time = date('Y-m-d H:i:s');
            $user->save();
        }

        //有推薦碼則寫入session 註冊預先帶入
        if (isset($request->refcode)) {
            $request->refcode = str_replace('?', '', $request->refcode);
            session(['referrer_code'=>$request->refcode]);
        }

        $domain = $request->server('HTTP_HOST');
        $share['seo'] = $this->domainSettingRepository->search(['domain'=>$domain])->result_one();

        View::share($share);
        return $next($request);
    }
}
