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

class NaRest
{

    protected $input = [],$config = [];

    public function __construct()
    {
        //得到数据流
        $this->input = Input()->any();

        //初始化本项目配置文件
        $this->config = Config(Request()->getProjectName())->get();
    }
}