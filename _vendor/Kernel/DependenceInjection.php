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
use \ArrayAccess;


class DependenceInjection implements ArrayAccess
{
    //服务实例
    protected static $instance = NULL;

    //注入池
    protected $data  = [] ;

    public static function init()
    {
        if(static::$instance == NULL)
        {
            static::$instance = new \NaRest\Kernel\DependenceInjection();
        }

        return static::$instance;
    }

    public function get($key , $default = NULL)
    {
        if (!isset($this->data[$key])) {
            $this->data[$key] = $default;
        }

        return $this->data[$key];
    }

    public function set($key , $val)
    {
        if(is_object($val))
            $this->data[$key] = $val;
        else
            $this->data[$key] = new $val();

        return $this;
    }

    public function __get($name)
    {
        return $this->get($name,NULL);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetGet($offset) {
        return $this->get($offset, NULL);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }
}