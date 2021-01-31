<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Wxuser;
use App\Libraries\MD5;
use Carbon\Carbon;

class CheckToken
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
        // var_dump($request->input());die;
        if ($request->has('token') && $request->has('user_id') && $request->has('server_code')) {
            $info = Wxuser::where('id', $request->input('user_id'))->first();
            if ($info == null) {
                return response()->json([
                    'data' => ['msg' => '不存在此openid'],
                    'code' => 1004,
                ], 200);
            } else {
                $md5 = new MD5();
                $token = $md5->make($request->input('user_id') . '/' . Carbon::now()->toDateString() . '/' . config('wx.key'));
                if ($token == $request->input('token')) {
                    return $next($request);
                }
            }
        } else {
            return response()->json([
                'data' => ['msg' => '请求错误'],
                'code' => 1004,
            ], 200);
        }
    }
}
