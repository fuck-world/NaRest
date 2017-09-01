<?php
// +----------------------------------------------------------------------
// | Author ：Naofbear
// +----------------------------------------------------------------------
// | Email : huakaiquan@qq.com
// +----------------------------------------------------------------------

/*
 * 支持的参数
 * is_enable 是否启用
 * exec_func 中间件启动的方法
 * conf_file 中间件需要载入的配置文件 --- 将在当前项目的Config目录下搜寻
 * throw_err 抛出的错误信息 列如授权错误 throw_err = ''
 * call_pass 调用传递 为exec传递参数 多参数请用数组格式
 * 支持的动作
 * after_get_request 在得到请求之后执行中间件
 * before_get_route  在得到请求之后且在寻找路由信息之前执行中间件
 * after_get_route   在得到路由信息之后执行
 * before_get_notFound 在notFound之前执行中间件
 * after_get_notFound  在notFound之后执行中间件
 * before_back_result  在返回结果之前执行
 * after_back_result   在返回结果之后执行
 * */

return [
    //在得到请求之后执行中间件 请注意:此刻并未执行路由 更未寻找控制器以及接口 请求接收到立刻执行
    'after_get_request'=>[
        '\\NaRest\\Components\\Middleware\\Automaton'=>[
            'is_enable'=>false,
            'exec_func'=>'exec',
            'conf_file'=>'Automaton',
            'throw_msg'=>'访问被禁止, Access forbid.',
            'call_pass'=>'',
        ]
    ],

    //在得到请求之后且在寻找路由信息之前执行中间件 与上级最大的区别 此时已经实例化核心应用 也就是说应用启动了。
    'before_get_route'=>[
        
    ],

    //在得到路由信息之后执行
    'after_get_route'=>[
        
    ],

    //在notFound之前执行中间件
    'before_get_notFound'=>[

    ],

    //在得到结果之前执行 此刻还未拿到执行数据
    'before_back_result'=>[

    ],

    //在得到结果之后执行 此刻已经拿到执行数据 但并未返回
    'after_back_result'=>[

    ]
];