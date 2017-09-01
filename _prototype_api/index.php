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

require_once dirname(__DIR__)."/_vendor/Bootstrap/Init.php";

try
{
    (new \NaRest\Kernel\NaApp())->response();
}
catch(\Exception $e)
{
    ErrorParse($e);
}


