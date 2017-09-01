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

use NaRest\Exception\ServerErrorException;

class View
{
    private $file = '';

    private $path = '';

    private $ext  = '.php';

    private $assign = [];

    public function __construct($file = '')
    {
        $this->path = PRO_PATH.'View'.DIRECTORY_SEPARATOR;

        if($file)
            $this->file = $file;
    }


    public function assign($data = [])
    {
        $this->assign = array_merge($this->assign,$data);
        return $this;
    }

    public function display()
    {
        if(file_exists($this->path.$this->file.$this->ext))
        {
            if($this->assign) extract($this->assign);

            include $this->path.$this->file.$this->ext;

            die();
        }
        else throw new ServerErrorException('template file is not found : '.$this->file,300);
    }
}