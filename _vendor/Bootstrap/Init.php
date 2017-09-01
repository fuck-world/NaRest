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

//定义框架信息
define('NA_VERSION','1.0.0');
define('NA_NAME','NaRest');

//定义根目录
define('NA_ROOT',dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR);

//定义组件目录
define('VE_PATH',NA_ROOT."_vendor".DIRECTORY_SEPARATOR);

//载入自动加载机制
$loader = require_once VE_PATH."Bootstrap/Autoload.php";

//载入必要公共文件
require_once VE_PATH."Functions.php";

//是否捕捉错误
if(Config(Request()->getProjectName())->get('debug'))
{
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}

//注册异常捕捉机制
set_exception_handler("ErrorException");
set_error_handler("ErrorHandle");

//时区设置
date_default_timezone_set(Config()->get('time_zone'));

//定义项目目录
define('PRO_PATH',Config()->get(Request()->getProjectName(),'Projects')['path']);

//初始化中间件并监听
if(Config(Request()->getProjectName())->get('enable_middleware'))
    Middleware(Config(Request()->getProjectName())->get('','Middleware'))->listen();

//第一个监控点
Middleware()->callMiddleware('after_get_request');
