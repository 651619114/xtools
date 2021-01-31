<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Res;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\MD5;
use App\Http\Controllers\Tools\Logs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $log;

    /**
     * xingmf
     * 后台父类控制器  目录权限判断
     */
    protected function _constructWeb(Res $request)
    {
        //控制器目录
        $url = '/' . $request->path();
        $num = substr_count($url, '/');
        if ($num == 3) {
            $url = substr($url, 0, strrpos($url, '/'));
        }

        $this->log = new Logs($request);

        view()->share('func', $url);
    }


    /**
     * xingmf
     * 前台父类控制器
     */
    protected function _constructApi(Res $request)
    {
    }

    /**
     * xingmf
     * 前台数据返回json格式化
     */
    public function success($msg = '', $data = array())
    {
        return response()->json([
            'message' => $msg,
            'data' => $data,
            'code' => 0,
        ], 200);
    }

    /**
     * xingmf
     * 前台数据返回json格式化
     */
    public function error($msg = '', $code)
    {
        return response()->json([
            'message' => $msg,
            'code' => $code,
        ], 200);
    }

    /**
     * xingmf
     * 前台数据返回json格式化
     */
    public function admin_error($msg = '')
    {
        return response()->json([
            'message' => $msg,
        ], 500);
    }

    /**
     * xingmf
     * 数据验证方法
     */
    public function func_validate(Res $request, $param)
    {
        $validate = config('validate.' . $param[0] . '.' . $param[1]);
        if (!empty($validate)) {
            return $request->validate($validate);
        } else {
            return '';
        }
    }
}
