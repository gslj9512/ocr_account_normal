<?php
namespace Ocr\tool;

use GuzzleHttp\Client;

class Http
{
    /**
     * @var object
     */
    protected $client;

    function __construct()
    {
        $this->client = new Client([
            'timeout'  => 11.0,
        ]);
    }

    /**
     * @param $url    string 请求地址
     * @param $header array  请求头
     * @param $data   array  请求体
     * @return string
     */
    function sendPost($url,$header,$data,$json=true){
        $content = $this->client->post($url,[
            'headers'=>$header,
            'form_params'=>$data
        ])->getBody()->getContents();
        return json_decode($content,true);
    }

}
