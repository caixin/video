<?php

namespace App\Http\Middleware;

use Closure;

class Benchmark
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
        benchmark()->checkpoint();
        return $next($request);
    }
}
