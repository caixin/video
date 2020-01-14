<?php

namespace App\Http\Middleware;

use Closure;

class ForceJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->shouldChangeAccept($request)) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }

    private function shouldChangeAccept($request)
    {
        $accepts = $request->headers->get('Accept');

        if (empty($accepts) === true) {
            return true;
        }

        return preg_match('/\*\/\*|\*|text\/html/', $accepts) === 1;
    }
}
