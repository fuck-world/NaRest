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

return [
    'core'=>[
        'name'=>'核心组件',
        'namespace'=>'NaRest',
        'path'=>NA_ROOT.'_vendor'.DIRECTORY_SEPARATOR
    ],
    
    '_prototype_api'=>[
        'name'=>'原型(API)',
        'appDirectory'=>'App',
        'namespace'=>'PrototypeApi',
        'format'=>'api',
        'path'=>NA_ROOT.'_prototype_api'.DIRECTORY_SEPARATOR
    ],

    '_prototype_html'=>[
        'name'=>'原型(HTML)',
        'appDirectory'=>'Controller',
        'namespace'=>'PrototypeHtml',
        'format'=>'html',
        'path'=>NA_ROOT.'_prototype_html'.DIRECTORY_SEPARATOR
    ]
];