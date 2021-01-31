<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AdminToolsOCR;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Libraries\OCR;

class ToolsController extends Controller
{
    public function __construct(Request $request)
    {
        parent::_constructWeb($request);
    }

    /**
     * xingmf
     * 用户列表
     */
    public function ocrToolLists(Request $request)
    {
        $lists = AdminToolsOCR::paginate(30);
        return view('tools.ocr', ['lists' => $lists]);
    }

    /**
     * xingmf
     * 用户列表-删除
     */
    public function create(Request $request)
    {
        $this->func_validate($request, ['admin', 'ocrdelete']);
        $info = AdminToolsOCR::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error("未找到信息");
        }
        $path = $info['source_path'];
        $ocr = new OCR();
        $res = $ocr->OCRrequest('http://127.0.0.1:8000/' . $path);
        $filename = uniqid() . '.txt';
        $filepath = 'txt/' . $request->session()->get('user_id') . '/' . $filename;

        Storage::put($filepath, '==================================文字识别结果==================================', 'public');
        if (isset($res['words_result'])) {
            foreach ($res['words_result'] as $key => $value) {
                Storage::append($filepath, $value['words']);
            }
        } else {
            return $this->admin_error('识别失败');
        }
        $res = AdminToolsOCR::where('id', $request->input('id'))->update(['to_path' => $filepath, 'status' => 2, 'file_name' => $filename]);
        if ($res > 0) {
            $this->log->info('文字识别成功 操作人[' . $request->session()->get('user_id') . ']');
            return $this->success('识别成功，点击下载按钮下载识别结果');
        } else {
            return $this->admin_error('识别失败');
        }
    }

    /**
     * xingmf
     * 用户列表-删除
     */
    public function ocrToolDelete(Request $request)
    {
        $this->func_validate($request, ['admin', 'ocrdelete']);
        $info = AdminToolsOCR::where('id', $request->input('id'))->first();
        if ($info == null) {
            return $this->admin_error("未找到信息");
        }

        $res = AdminToolsOCR::where('id', $request->input('id'))->delete();

        if ($res > 0) {
            $this->log->info('删除文字识别记录id[' . $res . ']成功');
            return $this->success('删除成功');
        } else {
            return $this->admin_error('删除失败');
        }
    }

    /**
     * xingmf
     * 用户列表-添加
     */
    public function ocrToolAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $this->func_validate($request, ['admin', 'ocradd']);
            $res = AdminToolsOCR::insertGetId(['source_path' => $data['source_path'], 'status' => 1, 'updated_at' => time(), 'created_at' => time(), 'user_id' => $request->session()->get('user_id'), 'to_path' => '', 'file_name' => '']);
            if ($res > 0) {
                $this->log->info('上传待识别图片id[' . $res . ']成功');
                return $this->success('上传图片成功');
            } else {
                return $this->admin_error('上传图片失败');
            }
        } else {
            return view('tools.ocr_add');
        }
    }


    public function upload(Request $request)
    {
        if ($request->isMethod('POST')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filepath = 'img/' . $request->session()->get('user_id') . '/' . uniqid() . '.' . $ext;
                $realPath = $file->getRealPath();
                $res = Storage::put($filepath, file_get_contents($realPath), 'public');
                if ($res) {
                    return $this->success('成功', ['filepath' => $filepath]);
                } else {
                    return $this->admin_error('上传失败');
                }
            }
        }
    }

    public function download(Request $request)
    {
        $info = AdminToolsOCR::where('id', $request->input('id'))->first();
        if ($info == null) {
            return redirect()->route('error', ['info' => '信息不存在']);
        }
        $exists = Storage::exists($info['to_path']);
        if (!$exists) {
            return redirect()->route('error', ['info' => '文件不存在']);
        }
        return Storage::download($info['to_path']);
    }
}
