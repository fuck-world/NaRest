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

function Di()
{
    return \NaRest\Kernel\DependenceInjection::init();
}

function Db($project = '')
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Db();
        Di()->set(__FUNCTION__,$object);
    }
    
    return $object->connect($project);
}

function Cache($project = '')
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Cache();
        Di()->set(__FUNCTION__,$object);
    }

    return $object->connect($project);
}

function Middleware($config = [])
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Middleware($config);
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Input()
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Input();
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Http()
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Http();
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Output()
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Output();
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Headers()
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Headers();
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Request()
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Request();
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Response()
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\Response();
        Di()->set(__FUNCTION__,$object);
    }

    return $object;
}

function Config($project = '')
{
    $execPath = $project ? (NA_ROOT.$project.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR):(VE_PATH.'Config'.DIRECTORY_SEPARATOR);
    
    return Loader($execPath);
}

function Loader($execPath = '')
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\FileLoader($execPath);
        Di()->set(__FUNCTION__,$object);
    }

    if(is_object($object)) Reflection($object)->setPrivateAttribute('path',$execPath);

    return $object;
}

function ErrorException($e)
{
    $data = [
        'message'=>$e->getMessage(),
        'info'=>[]
    ];

    if(Config(Request()->getProjectName())->get('debug'))
    {
        $data['info'] = [
            'err_level'=>$e->getCode(),
            'err_message'=>$e->getMessage(),
            'err_file'=>$e->getFile(),
            'err_line'=>$e->getLine(),
            'err_type'=>'error_exception'
        ];
    }

    Output()->results($e->getMessage(),500,$data);
}

function ErrorHandle($errLevel, $errString, $errFile, $errLine)
{
    $data = [
        'message'=>$errString,
        'info'=>[]
    ];

    if(Config(Request()->getProjectName())->get('debug'))
    {
        $data['info'] = [
            'err_level'=>$errLevel,
            'err_message'=>$errString,
            'err_file'=>$errFile,
            'err_line'=>$errLine,
            'err_type'=>'error_handle'
        ];
    }

    Output()->results($errString ? : 'internal error.' , 500 , $data);
}

function ErrorParse( $e = NULL )
{
    $data = [
        'message'=>$e->getMessage(),
        'info'=>[]
    ];

    if(Config(Request()->getProjectName())->get('debug'))
    {
        $data['info'] = [
            'err_level'=>$e->getCode(),
            'err_message'=>$e->getMessage(),
            'err_file'=>$e->getFile(),
            'err_line'=>$e->getLine(),
            'err_type'=>'error_parse'
        ];
    }

    Output()->results($e->getMessage(),$e->getCode() ? : 500 , $data);
}

function Reflection($obj = Null)
{
    return $object = new \NaRest\Kernel\Reflection($obj);
}

function View($file = '')
{
    if(($object = Di()->get(__FUNCTION__)) == NULL)
    {
        $object = new \NaRest\Kernel\View($file);
        Di()->set(__FUNCTION__,$object);
    }

    if(is_object($object)) Reflection($object)->setPrivateAttribute('file',$file);

    return $object;
}