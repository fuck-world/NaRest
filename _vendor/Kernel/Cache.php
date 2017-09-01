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

use \NaRest\Drives\Cache\MemcachedService;
use \NaRest\Drives\Cache\RedisService;

class Cache
{

    private $cacheMode = '' , $config = [] , $cache = [];

    private static $instance = [];

    public function connect($project = 'default')
    {
        $this->cacheMode = $project?:'default';

        if(!empty($this->cache[$this->cacheMode])) return $this->cache[$this->cacheMode];

        $this->config = Config()->get($this->cacheMode,'Cache');

        if(!isset(static::$instance[$this->cacheMode]))
        {
            if($this->config['use'] == 'memcached')
                static::$instance[$this->cacheMode] = new MemcachedService();
            else
                static::$instance[$this->cacheMode] = new RedisService();
        }
        
        return static::$instance[$this->cacheMode]->connect($this->config);
    }
}