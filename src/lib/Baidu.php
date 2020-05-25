<?php
namespace Ocr\lib;

use Ocr\tool\Http;
use Doctrine\Common\Cache\FilesystemCache;

class Baidu
{
    /**
     * access_token 地址
     * @var string
     */
    protected $auth_url = 'https://aip.baidubce.com/oauth/2.0/token';

    /**
     * 户口本识别地址
     * @var string
     */
    protected $household_url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/household_register';

    /**
     * 请求类型
     * @var string
     */
    protected $grant_type = 'client_credentials';

    /**
     * 百度ocr账户信息
     * @var array
     */
    protected $config = [
        'client_id'=>'',
        'client_secret'=>''
    ];

    /**
     * 请求密钥
     * @var string
     */
    protected $access_token;

    /**
     * http 工具类
     * @var Http
     */
    protected $http;

    /**
     * 缓存工具类
     * @var FilesystemCache
     */
    protected $cache;

    function __construct($config)
    {
        $this->config = array_merge($this->config,$config);
        $this->http = new Http();
        $this->cache = new FilesystemCache(sys_get_temp_dir());
        $this->access_token = $this->setAccessToken();
    }

    /**
     * 获取access_token
     * @return string
     */
    function setAccessToken()
    {
        $access_token = $this->cache->fetch('cache_baidu_ocr_access_token');
        if(!$access_token){
            $result = $this->http->sendPost($this->auth_url,[],[
                'grant_type'=>$this->grant_type,
                'client_id'=>$this->config['client_id'],
                'client_secret'=>$this->config['client_secret']
            ]);
            $this->cache->save('cache_baidu_ocr_access_token',$result['access_token'],$result['expires_in']);
            return $this->cache->fetch('cache_baidu_ocr_access_token');
        }else{
            return $access_token;
        }
    }

    /**
     * 户口本识别
     * @param $image
     * @return array
     */
    function houseHold($image){
        $header = ['Content-Type'=>'application/x-www-form-urlencoded'];
        $household_url = $this->household_url.'?access_token='.$this->access_token;
        $result = $this->http->sendPost($household_url,$header,[
            'image'=>$image
        ]);
        return $this->houseHoldPaser($result);
    }

    /**
     * 解析内容
     * @param $result
     * @return array
     */
    function houseHoldPaser($result){
        if(isset($result['words_result_num'])&&$result['words_result_num']){
            $words_info = $result['words_result'];
            return ['code'=>0,'msg'=>'获取信息成功','data'=>[
               'BirthAddress'   =>$words_info['BirthAddress']['words'],
               'Birthday'       =>$words_info['Birthday']['words'],
               'CardNo'         =>$words_info['CardNo']['words'],
               'Name'           =>$words_info['Name']['words'],
               'Nation'         =>$words_info['Nation']['words'],
               'Relationship'   =>$words_info['Relationship']['words'],
               'Sex'            =>$words_info['Sex']['words']
            ]];
        }else{
            return ['code'=>-1,'msg'=>'获取信息失败','data'=>[]];
        }
    }
}
