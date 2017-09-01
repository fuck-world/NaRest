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

namespace NaRest\Util;

class Regexp
{
    public static function dateTime($str = '')
    {
        $r = "/^\d{4}\-\d{2}\-\d{2}$/";

        if(preg_match($r,$str)) return true;else return false;
    }
}