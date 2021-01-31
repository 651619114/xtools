<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\AdminMenu;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        parent::_constructWeb($request);
    }


    public function index(Request $request)
    {
        return view('user.index');
    }

    /**
     * xingmf
     * 登陆页面
     */
    public function login(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('index');
        }

        if ($request->isMethod('post')) {
            if (!$request->filled('email') || !$request->filled('password'))
                return view('user.login', array('msg' => '邮箱与密码均为必填'));
            if (Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'status' => 1])) {
                $menu = new AdminMenu();
                $data = $menu->menuInit(Auth::guard('web')->user()->role_id);

                session(["user_id" => Auth::guard('web')->user()->id, "role_id" => Auth::guard('web')->user()->role_id, "menu" => $data[0], "func_class" => $data[1], 'name' => Auth::guard('web')->user()->name, 'user_status' =>  Auth::guard('web')->user()->status]);
                $this->log->info('用户id[' . Auth::guard('web')->user()->id . ']登陆成功');
                return redirect()->route('index');
            } else {
                return view('user.login', array('msg' => '失败'));
            }
        }

        return view('user.login');
    }

    /**
     * xingmf
     * 退出登录
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('index');
    }
}
