<?php

namespace App\Libraries;

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class QNCloud
{
    private $_bucket = "xmfcloud";
    private $_auth;
    public function __construct()
    {
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = "AD67G0jKaojLAbNsozMXiAt4AyddS3MfNFcvfBbP";
        $secretKey = "9C3OWEPDNy4y28goB6sgFC1fn-rcL5KexQqfO0ib";
        // 构建鉴权对象
        $this->_auth = new Auth($accessKey, $secretKey);
    }

    public function upload($filePath, $filename)
    {
        $token = $this->_auth->uploadToken($this->_bucket);

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $filename, $filePath);
        if ($err !== null) {
            return ['res' => false, 'err' => $err];
        } else {
            return ['res' => true, 'err' => null];
        }
    }

    public function download($fileName = '')
    {
        if (!empty($fileName)) {
            $baseUrl = 'http://qnxip9k3n.hb-bkt.clouddn.com/' . $fileName;
            $this->_downLoadUrl = $this->_auth->privateDownloadUrl($baseUrl);
        } else {
            $this->_downLoadUrl = '';
        }
        return $this->_downLoadUrl;
    }

    public function delete($filename = '')
    {
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($this->_auth, $config);
        $err = $bucketManager->delete($this->_bucket, $filename);
        if ($err !== null) {
            return ['res' => false, 'err' => $err];
        } else {
            return ['res' => true, 'err' => null];
        }
    }
}
