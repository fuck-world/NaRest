<?php
// +----------------------------------------------------------------------
// | NaRest [ Restful微型框架 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.naofbear.com All rights reserved.
// +----------------------------------------------------------------------
// | The author has all the explanatory power of this framework
// +----------------------------------------------------------------------
// | Author: naofbear wechat: naofbear email: huakaiquan@qq.com
// +----------------------------------------------------------------------

namespace NaRest\Components\Middleware;

class Authorization
{

    private $auth = [];

    public function __construct($config = [])
    {
        $this->auth = $config;
    }

    public function exec()
    {
        $app_id = Input()->any('app_id');

        if(empty($app_id) || !isset($this->auth[$app_id])) return false;
        
        return $this->checkAuthorization($this->auth[$app_id]);
    }

    private function checkAuthorization($authInfo = [])
    {
        //验证哪些接口需要验证
        if(!$this->checkAuthApi($authInfo['authentication_mode'])) return true;

        //验证IP是否合法
        if(!$this->checkAuthIpIsAllow($authInfo['app_bind_ip'])) return false;

        //验证签名
        if(!$this->checkAuthSign($authInfo['app_key'])) return false;

        //验证应用是否已过期
        if(!$this->checkAuthIsLapse($authInfo['app_lapse_time'])) return false;

        //验证应用是否有资源可调用
        if(!$this->checkAuthResources($authInfo['app_request_limit_type'],$authInfo['app_request_limit'])) return false;

        //验证应用并发是否超过限制
        if(!$this->checkAuthConnect($authInfo['app_max_connect'])) return false;

        return true;
    }

    private function checkAuthApi($checkRule = [])
    {
        //得到当前调用的接口
        $api = Request()->getClassName().'.'.Request()->getActionName();

        //得到批量验证规则接口名
        $apiRule = strstr($api,'.',true).'.';

        if(!empty($checkRule['disable']))
        {
            //得到新验证数组
            $disable = $checkRule['disable'] == '*' ? '*' : str_replace('*','',$checkRule['disable']);

            //当前访问接口是否介于允许规则中 存在则返回跳过验证
            if(is_array($disable) && (in_array($api,$disable) || in_array($apiRule,$disable)))
                return false;
            elseif(is_string($disable) && ($disable == '*' || $disable == $api || $disable == $apiRule))
                return false;
        }

        if(!empty($checkRule['enable']))
        {
            //得到新验证数组
            $enable = $checkRule['enable'] == '*' ? '*' : str_replace('*','',$checkRule['enable']);

            //当前访问接口是否介于允许规则中 存在则必须验证
            if(is_array($enable) && (in_array($api,$enable) || in_array($apiRule,$enable)))
                return true;
            elseif(is_string($enable) && ($enable == '*' || $enable == $api || $enable == $apiRule))
                return true;
        }

        return $checkRule['default'] ? true : false;
    }

    private function checkAuthIpIsAllow($ip = NULL)
    {
        if(empty($ip)) return true;

        if(is_array($ip) && !in_array(Input()->ip(),$ip)) return false;

        if(is_string($ip) && Input()->ip() != $ip) return false;

        return true;
    }

    private function checkAuthSign($appKey = '')
    {
        $apiName = implode('/',[lcfirst(Request()->getClassName()),Request()->getActionName()]);



        $timestamp =(int)Input()->any('t');

        if(empty($timestamp) || strlen($timestamp) !== strlen(time())) return false;

        $sign = md5($appKey.$apiName.$timestamp);

        if($sign == Input()->any('sign'))
            return true;
        
        return false;
    }

    private function checkAuthIsLapse($lapseTime = '')
    {
        if(!empty($lapseTime) && strtotime($lapseTime) <= time()) return false;
        
        return true;
    }

    private function checkAuthConnect($maxConnectNum = 0)
    {
        if(empty($maxConnectNum)) return true;

        //todo
        return true;
    }

    private function checkAuthResources($type = '', $num = '')
    {
        if($type == 'not' || empty($num)) return true;

        //todo all & day 目前先不验证

        return true;
    }
}
