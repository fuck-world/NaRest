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

class FileLoader
{

    private  $path = '',$ex = '';

    public function __construct($initPath = '',$ex = '.php')
    {
        $this->path = $initPath;
        $this->ex   = $ex;
    }

    public function get($key = '',$file = '')
    {
        $content = $this->loadFile($file?:'Config');

        if(empty($content)) return NULL;

        if(empty($key)) return $content;

        if(isset($content[$key])) return $content[$key];else return $content;

    }

    private function loadFile($file = '')
    {
        $filePath = $this->path.$file.$this->ex;
        if(is_file($filePath)) return include $filePath; else return false;
    }
}