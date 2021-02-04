<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Model\Cloud;
use App\Libraries\QNCloud;

class CloudController extends Controller
{
    public function __construct(Request $request)
    {
        parent::_constructWeb($request);
    }

    /**
     * xingmf
     * 用户列表
     */
    public function cloudLists(Request $request)
    {
        $lists = Cloud::paginate(30);
        return view('cloud.cloud', ['lists' => $lists]);
    }

    /**
     * xingmf
     * 用户列表-删除
     */
    public function create(Request $request)
    {
        $this->func_validate($request, ['admin', 'ocrdelete']);
        $info = Cloud::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error("未找到信息");
        }
        if (Storage::exists($info['file_name'])) {
            $qn = new QNCloud();
            $res = $qn->upload(Storage::path($info['file_name']), $info['real_name']);
            if ($res['res']) {
                $bool = Cloud::where('id', $request->input('id'))->update(['is_sync' => 2]);
                if ($bool) {
                    $this->log->info('上传文件至云端成功，真实文件名：' . $info['real_name'] . '本地文件名：' . $info['file_name']);
                    return $this->success('同步成功');
                }
            } else {
                //邮件
                $this->log->info('上传文件至云端失败，真实文件名：' . $info['real_name'] . '本地文件名：' . $info['file_name']);
                return redirect()->route('mail', ['info' => '上传至云端失败']);
            }
        } else {
            return $this->admin_error('文件不存在');
        }
    }

    /**
     * xingmf
     * 用户列表-删除
     */
    public function cloudDelete(Request $request)
    {
        $this->func_validate($request, ['admin', 'ocrdelete']);
        $info = Cloud::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error("未找到信息");
        }
        if (Storage::exists($info['file_name'])) {
            //本地文件转入trunk文件夹备份
            Storage::move($info['file_name'], 'trush/' . $info['file_name']);
        }
        $qn = new QNCloud();
        $res = $qn->delete($info['real_name']);
        if (!$res['res']) {
            return redirect()->route('mail', ['info' => '云端文件删除失败']);
        }

        $res = Cloud::where('id', $request->input('id'))->delete();

        if ($res > 0) {
            $this->log->info('删除文件[' . $info['real_name'] . ']成功');
            return $this->success('删除成功');
        } else {
            return $this->admin_error('删除失败');
        }
    }


    public function upload(Request $request)
    {
        $is_complete = false;

        $chunk = $request->input('chunk', 0);
        $chunks = $request->input('chunks', 0);
        $file = $request->file('file');

        $realName = $file->getClientOriginalName();
        $realPath = $file->getRealPath();
        $ext = $file->getClientOriginalExtension();
        $newFileName = 'file/' . $request->session()->get('user_id') . '_' . date('Y-m-d-H-i-S') . '_' . uniqid() . '.' . $ext;
        if ($chunk == $chunks) {
            //直接保存
            $bool = Storage::put($newFileName, file_get_contents($realPath));
            $path = Storage::path($newFileName);
            $is_complete = true;
        } else {
            //移入切片目录
            $silenceName = md5($realName) . '_' . ($chunk + 1) . '.tmp';
            $bool = Storage::disk('tmp')->put($silenceName, file_get_contents($realPath));
            if (($chunk + 1) == $chunks) {
                for ($i = 1; $i <= $chunks; $i++) {
                    $blob = Storage::disk('tmp')->get(md5($realName) . '_' . $i . '.tmp');
                    $path = Storage::path($newFileName);
                    file_put_contents($path, $blob, FILE_APPEND);
                }
                //合并完删除文件块
                for ($i = 1; $i <= $chunks; $i++) {
                    Storage::disk('tmp')->delete(md5($realName) . '_' . $i . '.tmp');
                }
                $is_complete = true;
            }
            if (!$is_complete && $bool) {
                return $this->success('上传成功');
            }
        }
        if ($is_complete) {
            $size = round((Storage::size($newFileName) / 1024), 2) . ' kb';
            if ($bool) {
                $res = Cloud::insertGetId(['user_id' => $request->session()->get('user_id'), 'real_name' => $realName, 'file_name' => $newFileName, 'remote_path' => '', 'file_size' => $size, 'is_sync' => 1, 'created_at' => time()]);
                if ($res) {
                    $this->log->info('上传文件[' . $newFileName . ']成功');
                    return $this->success('上传成功');
                } else {
                    return $this->admin_error('数据库操作失败');
                }
            } else {
                return $this->admin_error('上传失败');
            }
        }
    }

    public function download(Request $request)
    {
        $this->func_validate($request, ['admin', 'ocrdelete']);
        $info = Cloud::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error("未找到信息");
        }
        if (Storage::exists($info['file_name'])) {
            $qn = new QNCloud();
            $res = $qn->download($info['real_name']);
            if (!empty($res)) {
                $bool = Cloud::where('id', $request->input('id'))->update(['remote_path' => $res]);
                return $this->success('云端下载直链生成成功', ['url' => $res]);
            } else {
                //邮件
                return redirect()->route('mail', ['info' => '云端直链生成失败']);
            }
        } else {
            return $this->admin_error('文件不存在');
        }
    }

    public function test()
    {
        return redirect()->route('mail', ['info' => '测试']);
    }
}
