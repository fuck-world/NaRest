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

namespace NaRest\Drives\Cache;

use \Memcached;

class MemcachedService
{
    public function connect($config = [])
    {
        $m = new Memcached();
        $m->addServer($config['host'],$config['port']);
        return $m;
    }
}