<?php

namespace App\Libraries;

class OCR
{
    private $_token = array();
    public function __construct()
    {
        $this->getToken();
    }

    public function getToken()
    {
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $sl_data = array(
            'grant_type' => "client_credentials",
            'client_id' => 'dcxZhtGgu6ehbT2fB0WGiQ7v',
            "client_secret" => "8WGyjQ1xOt6Dl9pBlzlnBdThewNrTTo7"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sl_data);
        $output = curl_exec($ch); //执行并获取数据
        curl_close($ch);
        $this->_token = json_decode($output, TRUE);
    }

    public function OCRrequest($path)
    {
        $token = '';
        if (isset($this->_token['access_token'])) {
            $token = $this->_token['access_token'];
        } else {
            return false;
        }
        $url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate_basic?access_token=' . $token;

        $img = file_get_contents('https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=384723285,2838454529&fm=26&gp=0.jpg');

        $img = base64_encode($img);
        $chPost = [
            "image" => $img,
            "language_type" => 'auto_detect',
            "paragraph" => "true"
        ];
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $chPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data, true);
    }
}
