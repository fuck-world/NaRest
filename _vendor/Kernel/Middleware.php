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

namespace NaRest\Kernel;

class Middleware
{

    private $config = [] , $middlewareIsEnable = false , $errMsg = 'internal error.' , $isErr = false;

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function listen()
    {
        $this->middlewareIsEnable = true;
    }

    public function getConfig($key = '')
    {
        if($key && isset($this->config[$key]))
            return $this->config[$key];
        elseif($key && empty($this->config[$key]))
            return [];

        return $this->config;
    }

    public function isEnable()
    {
        return $this->middlewareIsEnable;
    }

    public function callMiddleware($key = '')
    {
        if($this->isEnable() === false)
            return true;

        if(!($call = Middleware()->getConfig($key))) return true;
        
        foreach ($call as $k=>$v)
        {
            //未启用,跳过
            if($v['is_enable'] === false) continue;

            //得到当前处理类
            $c = basename(str_replace('\\','/',$k));

            //得到配置文件
            if($v['conf_file'] && !($config = Config(Request()->getProjectName())->get('',$v['conf_file'])))
            {
                $this->errMsg = 'load config file error at :'.$c;
                $this->isErr = true;
                goto TryError;
            }

            //验证方法是否存在
            if(!class_exists($k))
            {
                $this->errMsg = 'middleware is not found at :'.$c;
                $this->isErr = true;
                goto TryError;
            }

            //验证执行方法是否存在
            $object = $v['conf_file'] && isset($config) ? new $k($config) : new $k();
            if(!method_exists($object,$v['exec_func']) || !is_callable([$object,$v['exec_func']]))
            {
                $this->errMsg = 'exec func is not found at : '.$c.' and func is : '.$v['exec_func'];
                $this->isErr = true;
                goto TryError;
            }


            $param = isset($v['call_pass'])?(is_array($v['call_pass'])?$v['call_pass']:[$v['call_pass']]):[];

            if(!call_user_func_array([$object,$v['exec_func']],$param))
            {
                $this->errMsg = !empty($v['throw_msg']) ? $v['throw_msg'] : $this->errMsg;
                $this->isErr = true;
                goto TryError;
            }
        }

        if($this->isErr)
        {
            TryError:
            throw new \NaRest\Exception\MiddlewareErrorException($this->getErrorMsg(),999);
        }

        return true;
    }

    public function getErrorMsg()
    {
        return $this->errMsg;
    }
}
