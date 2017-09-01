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
use \NaRest\Exception\RequestErrorException;
use NaRest\Exception\ServerErrorException;

class NaApp
{
    public function response()
    {

        //第二个监控点
        Middleware()->callMiddleware('before_get_route');

        $namespace = Request()->getNamespace();
        $action    = Request()->getActionName();
        
        if(empty($action))
            Output()->results('Welcome To NaRest Api Restful. ->>> '.(Headers()->get('SERVER_NAME')),0,'');

        //第三个监控点
        Middleware()->callMiddleware('after_get_route');

        if(empty($namespace) || empty($action) || !class_exists($namespace))
        {
            //第四个监控点 触发notFound之前触发
            Middleware()->callMiddleware('before_get_notFound');
            throw new RequestErrorException('file is not found.',300);
        }

        $object = new $namespace();

        if(!method_exists($object,$action) || !is_callable([$object,$action]))
        {
            //第四个监控点 触发notFound之前触发
            Middleware()->callMiddleware('before_get_notFound');
            throw new RequestErrorException('no such service as '.$action,302);
        }

        try
        {
            //第五个监控点 得到结果之前触发
            Middleware()->callMiddleware('before_back_result');

            $result = call_user_func([$object,$action]);

            //第六个监控点 得到结果之后触发
            Middleware()->callMiddleware('after_back_result');

            //返回结果集
            Output()->results('操作成功','0',$result);
        }
        catch (\Exception $e)
        {
            ErrorParse($e);
        }
    }
}