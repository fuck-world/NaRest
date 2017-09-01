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

class Response
{
    public function json($msg = 'success' , $status = 0 , $data = [])
    {

        if(empty($data) && empty($msg) && $status == 0)
        {
            $msg = '查询的数据不存在';
            $status = 999;
        }
        else
        {
            $msg = $msg ? : '操作成功!';
            $status = $status ? : 0;
        }

        echo json_encode(is_array($msg) ? $msg : [
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data?:[]
        ]);
        
        exit();
    }
}