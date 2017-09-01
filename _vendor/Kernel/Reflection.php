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

class Reflection
{

    private $object , $reflection ;

    public function __construct($obj)
    {
        $this->object     = $obj;
        $this->reflection = new \ReflectionClass($obj);
    }

    public function setPrivateAttribute($key , $val )
    {
        $attribute = $this->reflection->getProperty($key);
        $attribute->setAccessible(true);
        $attribute->setValue($this->object,$val);
    }
}