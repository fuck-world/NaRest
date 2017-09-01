<?php
// +----------------------------------------------------------------------
// | Author ：Naofbear
// +----------------------------------------------------------------------
// | Email : huakaiquan@qq.com
// +----------------------------------------------------------------------

return [
    '156hfjbczdgw444kmcvxd21321'=>[
        //应用名称
        'app_name'=>'test',
        //应用别名
        'app_alias'=>'test',
        //应用KEY私钥
        'app_key'=>'xawxaxwadascxzfdsghfkdr343rdscf3',
        //应用授权时间 标准格式: Y-m-d H:i:s 或者 Y-m-d
        'app_authorization_time'=>'2017-08-16',
        //应用失效时间 标准格式: Y-m-d H:i:s 或者 Y-m-d 空为永久有效
        'app_lapse_time'=>'',
        //应用最大并发连接数 秒级别 0为不限制
        'app_max_connect'=>0,
        //应用请求限制类型 [day/all/not] day 按天 all 按请求总量 not 不限制
        'app_request_limit_type'=>'not',
        //应用请求限制次数 根据限制类型定义限制次数
        'app_request_limit'=>0,
        //应用是否绑定IP 支持多个 设置将只认该名单中IP的请求 空为不限制
        'app_bind_ip'=>'',
        //身份验证模式
        'authentication_mode'=>[
            //启用验证 * 代表所有接口都验证 优先验证 disable 后续验证 enable & 请注意,如果规则都不符合,都不需要验证
            //最优方式 disable 设置一部分 enable 设置为 *
            //class.action 指定Class至方法Action [验证-enable/跳过-disable] 范例: Activity.lists
            //class.* 当前Class所有方法都会 [验证-enable/跳过-disable] 范例: Activity.*
            //default 默认模式 true 验证 false 跳过
            'disable'=>'',
            'enable'=>'*',
            'default'=>true
        ]
    ]
];