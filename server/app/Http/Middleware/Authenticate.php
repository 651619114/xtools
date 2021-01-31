<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, $guard = 'web')
    {

        if (Auth::guard($guard)->check()) {
            $url = '/' . $request->path();
            $num = substr_count($url, '/');
            if ($num == 3) {
                $url = substr($url, 0, strrpos($url, '/'));
            }
            if (!in_array($url, $request->session()->get('func_class'))) {
                return redirect()->route('error');
            }

            //服务日期及个人状态判断
            if ($request->session()->has('start_time') && $request->session()->has('end_time') && $request->session()->get('role_id') != 1) {

                if ((time() >= $request->session()->get('end_time')) || (time() <= $request->session()->get('start_time')) || ($request->session()->get('user_status') != 1) || ($request->session()->get('server_status') != 1)) {
                    return redirect()->route('error');
                }
            }

            return $next($request);
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('login');
            }
        }
    }
}
