<?php

namespace App\Libraries;

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class QNCloud
{

    private $_token;
    private $_downLoadUrl = '';
    public function __construct($type = 'upload', $fileName = '')
    {
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = "AD67G0jKaojLAbNsozMXiAt4AyddS3MfNFcvfBbP";
        $secretKey = "9C3OWEPDNy4y28goB6sgFC1fn-rcL5KexQqfO0ib";
        $bucket = "xmfcloud";
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        if ($type == 'upload') {
            // 生成上传 Token
            $this->_token = $auth->uploadToken($bucket);
        } else if ($type == 'download') {
            if (!empty($fileName)) {
                $baseUrl = 'http://qnxip9k3n.hb-bkt.clouddn.com/' . $fileName;
                $this->_downLoadUrl = $auth->privateDownloadUrl($baseUrl);
            } else {
                $this->_downLoadUrl = '';
            }
        }
    }

    public function upload($filePath, $filename)
    {
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($this->_token, $filename, $filePath);
        if ($err !== null) {
            return ['res' => false, 'err' => $err];
        } else {
            return ['res' => true, 'err' => null];
        }
    }

    public function download()
    {
        return $this->_downLoadUrl;
    }
}
