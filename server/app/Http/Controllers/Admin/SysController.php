<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\AdminMenu;
use App\Libraries\MD5;
use App\Model\AdminRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Generator\StringManipulation\Pass\Pass;

class SysController extends Controller
{
    public function __construct(Request $request)
    {
        parent::_constructWeb($request);
    }

    /**
     * xingmf
     * 用户列表
     */
    public function userLists(Request $request)
    {
        $lists = User::paginate(30);
        return view('sys.user', ['lists' => $lists]);
    }

    /**
     * xingmf
     * 用户列表-状态切换
     */
    public function userChange(Request $request)
    {
        $this->func_validate($request, ['admin', 'userchange']);
        $info = User::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error('未找到用户信息');
        }
        if ($info->status == 1) {
            $status = 2;
        } else {
            $status = 1;
        }
        $res = User::where('id', $request->input('id'))->update(['status' => $status]);

        if ($res > 0) {
            $this->log->info('更新用户id[' . $res . ']状态');
            return $this->success('更新成功');
        } else {
            return $this->admin_error('更新失败');
        }
    }

    /**
     * xingmf
     * 用户列表-删除
     */
    public function userDelete(Request $request)
    {
        $this->func_validate($request, ['admin', 'userdelete']);
        $info = User::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error("未找到用户信息");
        }

        $res = User::where('id', $request->input('id'))->delete();

        if ($res > 0) {
            $this->log->info('删除用户id[' . $res . ']成功');
            return $this->success('删除成功');
        } else {
            return $this->admin_error('删除失败');
        }
    }

    /**
     * xingmf
     * 用户列表-添加
     */
    public function userAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $this->func_validate($request, ['admin', 'useradd']);
            $md5 = new MD5();
            $res = User::insertGetId(['name' => $data['name'], 'phone' => $data['phone'], 'email' => $data['email'], 'remember_token' => '1', 'role_id' => $data['role_id'], 'status' => 1, 'password' => $md5->make($data['password']), 'updated_at' => time(), 'created_at' => time()]);
            if ($res > 0) {
                $this->log->info('添加用户id[' . $res . ']成功');
                return $this->success('添加用户成功');
            } else {
                return $this->admin_error('添加用户失败');
            }
        } else {
            return view('sys.user_add');
        }
    }

    /**
     * xingmf
     * 用户列表-状态修改
     */
    public function userModify(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $this->func_validate($request, ['admin', 'usermodify']);
            $md5 = new MD5();
            $res = User::where('id', $data['id'])->update(['name' => $data['name'], 'phone' => $data['phone'], 'email' => $data['email'], 'remember_token' => '1', 'role_id' => $data['role_id'], 'status' => 1, 'password' => $md5->make($data['password'])]);
            if ($res > 0) {
                $this->log->info('更新用户id[' . $res . ']成功');
                return $this->success('更新成功');
            } else {
                return $this->admin_error('更新失败');
            }
        } else {
            $info = User::where('id', $request->input('id'))->first();
            if ($info == null) {
                return $this->admin_error('未找到用户信息');
            }
            return view('sys.user_modify', ['info' => $info]);
        }
    }

    /**
     * xingmf
     * 用户列表
     */
    public function menuLists(Request $request)
    {
        $lists = AdminMenu::orderBy('root_id')->paginate(200);
        $role = AdminRole::get()->toArray();
        $role_menu = array();
        foreach ($role as $key => $value) {
            if ($value['role_menu'] != '*') {
                $role_menu[$value['role_name']] = explode(',', $value['role_menu']);
            }
        }
        foreach ($lists as $key => &$value) {
            $checkRole = array();

            foreach ($role_menu as $k => $v) {
                if (in_array($value->menu_id, $v)) {
                    $checkRole[] = $k;
                }
            }
            if (!empty($checkRole)) {
                $checkRole = implode(',', array_unique($checkRole));
            } else {
                $checkRole = '';
            }
            $value->checkrole = $checkRole;
        }
        return view('sys.menu', ['lists' => $lists]);
    }


    /**
     * xingmf
     * 用户列表-添加
     */
    public function menuAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $this->func_validate($request, ['admin', 'menuadd']);
            $info = AdminMenu::where('name', $data['name'])->first();
            if ($info != null) {
                return $this->admin_error('已存在此目录');
            }
            $res = AdminMenu::insertGetId(['name' => $data['name'], 'root_id' => $data['root_id'], 'display' => $data['display'], 'class_func' => $data['class_func'], 'is_menu' => 1]);
            if (isset($data['role_menu']) && !empty($data['role_menu'])) {
                $roleInfo = AdminRole::where('role_id', $data['role_menu'])->first()->toArray();
                if (!empty($roleInfo)) {
                    $roleMenu = explode(',', $roleInfo['role_menu']);
                    array_push($roleMenu, $res);
                    $roleMenu = implode(',', $roleMenu);
                    $roleRes = AdminRole::where('role_id', $data['role_menu'])->update(['role_menu' => $roleMenu]);
                }
            }
            if ($res > 0 && $roleRes > 0) {
                $this->log->info('添加目录menu_id[' . $res . ']成功');
                return $this->success('添加成功');
            } else {
                return $this->admin_error('添加失败');
            }
        } else {
            $roleInfo = AdminRole::where('role_menu', '!=', '*')->get()->toArray();
            if (!empty($roleInfo)) {
                $roleInfo = array_column($roleInfo, 'role_name', 'role_id');
            } else {
                return $this->admin_error('未找到角色信息');
            }

            $rootMenu = AdminMenu::where('root_id', 0)->get()->toArray();
            return view('sys.menu_add', ['role' => $roleInfo, 'rootmenu' => $rootMenu]);
        }
    }



    public function menuModify(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $this->func_validate($request, ['admin', 'menumodify']);
            $info = AdminMenu::where('menu_id', $data['menu_id'])->first();
            if ($info == null) {
                return $this->admin_error('不存在此目录');
            }
            $res = AdminMenu::where('menu_id', $request->input('menu_id'))->update(['name' => $data['name'], 'root_id' => $data['root_id'], 'display' => $data['display'], 'class_func' => $data['class_func'], 'is_menu' => 1]);
            if (isset($data['role_menu']) && !empty($data['role_menu'])) {
                $roleInfo = AdminRole::where('role_id', $data['role_menu'])->first()->toArray();
                if (!empty($roleInfo)) {
                    $roleMenu = explode(',', $roleInfo['role_menu']);
                    if (!in_array($request->input('menu_id'), $roleMenu)) {
                        array_push($roleMenu, $request->input('menu_id'));
                        $roleMenu = implode(',', array_unique($roleMenu));
                        $roleRes = AdminRole::where('role_id', $data['role_menu'])->update(['role_menu' => $roleMenu]);
                    } else {
                        $roleRes = 1;
                    }
                }
            } else {
                $roleInfo = AdminRole::where('role_id', 2)->first()->toArray();
                if (!empty($roleInfo)) {
                    $roleMenu = explode(',', $roleInfo['role_menu']);
                    if (!in_array($request->input('menu_id'), $roleMenu)) {
                        $roleRes = 1;
                    } else {
                        foreach ($roleMenu as $key => $value) {
                            if ($value == $request->input('menu_id')) {
                                unset($roleMenu[$key]);
                            }
                        }
                        $roleMenu = implode(',', array_unique($roleMenu));
                        $roleRes = AdminRole::where('role_id', 2)->update(['role_menu' => $roleMenu]);
                    }
                }
            }
            if ($res > 0 && $roleRes > 0) {
                $this->log->info('添加目录menu_id[' . $res . ']成功');
                return $this->success('添加成功');
            } else {
                return $this->admin_error('添加失败');
            }
        } else {
            $this->func_validate($request, ['admin', 'menudelete']);
            $roleInfo = AdminRole::where('role_menu', '!=', '*')->get()->toArray();

            if (!empty($roleInfo)) {
                $role = array_column($roleInfo, 'role_name', 'role_id');
            } else {
                return $this->admin_error('未找到角色信息');
            }

            $rootMenu = AdminMenu::where('root_id', 0)->get()->toArray();
            $roleMenuArr = array();

            if (!empty($roleInfo)) {
                foreach ($roleInfo as $key => $value) {
                    $roleMenu = explode(',', $value['role_menu']);
                    if (in_array($request->input('menu_id'), $roleMenu)) {
                        $roleMenuArr[] = $value['role_id'];
                    }
                }
            }
            $menuInfo = AdminMenu::where('menu_id', $request->input('menu_id'))->first()->toArray();

            return view('sys.menu_modify', ['role' => $role, 'rootmenu' => $rootMenu, 'menuinfo' => $menuInfo, 'rolemenuarr' => $roleMenuArr]);
        }
    }

    /**
     * xingmf
     * 用户列表-删除
     */
    public function menuDelete(Request $request)
    {
        $this->func_validate($request, ['admin', 'menudelete']);
        $info = AdminMenu::where('menu_id', $request->input('menu_id'))->first();
        if ($info == null) {
            return $this->admin_error('未找到菜单信息');
        }

        $res = AdminMenu::where('menu_id', $request->input('menu_id'))->delete();
        $root_res = AdminMenu::where('root_id', $request->input('menu_id'))->delete();

        if ($res > 0 && $root_res) {
            $this->log->info('删除服务menu_id[' . $res . ']成功');
            return $this->success('删除成功');
        } else {
            return $this->admin_error('删除失败');
        }
    }


    public function upload(Request $request)
    {
        if ($request->isMethod('POST')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filepath = $request->session()->get('server_code') . '/'
                    . uniqid() . '.' . $ext;
                $realPath = $file->getRealPath();
                $res = Storage::disk('upload_image')->put($filepath, file_get_contents($realPath), 'public');
                if ($res) {
                    $path = 'storage/upload/images/' . $filepath;
                    return $this->success('成功', ['filepath' => $path]);
                } else {
                    return $this->admin_error('上传失败');
                }
            }
        }
    }
}
