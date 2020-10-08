<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminLogin
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
        if(!auth()->check()){
            return redirect(route('admin.login'))->withErrors(['errors'=>'请登录']);
        }
        // 如果没有停止则向后执行
        return $next($request);
    }
}
