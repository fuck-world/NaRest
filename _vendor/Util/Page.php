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

namespace NaRest\Util;

class Page {
    /*
     * 根据总页数计算分页
     * @nowPage 当前页
     * @totalPages 总页数
     * @params 辅助参数
     *    @ prev_class 上一页class
     *    @ curr_class 当前页class
     *    @ next_class 下一页class
     *    @ show_pages 显示的分页数量
     *    @ show_next 是否显示下一页
     *    @ show_prev 是否显示上一页
     *    @ prev_text 上一页文字
     *    @ next_text 下一页文字
     * */

    static function byTotalPages($nowPage = 1,$totalPages,$params = []){

        $params = array_merge([
            'prev_class'=>'pre-page',
            'curr_class'=>'cur',
            'next_class'=>'next-page',
            'show_pages'=>10,
            'show_next'=>true,
            'show_prev'=>true,
            'prev_text'=>'<i></i>上一页',
            'next_text'=>'下一页<i></i>'
        ],$params);


        if($totalPages <= 0){
            return [];
        }

        if(empty($nowPage))$nowPage = 1;

        if($totalPages <= $params['show_pages']){
            $start = 1;
            $end   = $totalPages;
        }elseif($nowPage+$params['show_pages'] > $totalPages){
            $start = $totalPages-$params['show_pages'];
            $end   = $totalPages;
        }else{
            $start = $nowPage;
            $end = ($params['show_pages']+$nowPage) - 1;
        }

        if($nowPage == 1){
            $params['show_prev'] = false;
        }

        if($nowPage == $totalPages){
            $params['show_next'] = false;
        }

        $page = [];

        if($params['show_prev']){
            array_push($page,[
                'text'=>$params['prev_text'],
                'num'=>$nowPage > 1 ? $nowPage - 1 : 1,
                'type'=>'prev',
                'class'=>$params['prev_class']
            ]);
        }

        for($i = $start;$i<= $end; $i++){
            $page[] = [
                'text'=>$i,
                'num'=>$i,
                'type'=>'page',
                'class'=> $i==$nowPage ? $params['curr_class']:''
            ];
        }

        if($params['show_next']){
            array_push($page,[
                'text'=>$params['next_text'],
                'num'=>$nowPage < $totalPages ? $nowPage + 1: $totalPages,
                'page'=>'next',
                'class'=>$params['next_class']
            ]);
        }

        return $page;
    }

    /*
     * 根据总条数计算分页
     * @nowPage 当前页
     * @totalRows 总页数
     * @showRows 每页显示的条数
     * @params 辅助参数
     *    @ prev_class 上一页class
     *    @ curr_class 当前页class
     *    @ next_class 下一页class
     *    @ show_pages 显示的分页数量
     *    @ show_next 是否显示下一页
     *    @ show_prev 是否显示上一页
     *    @ prev_text 上一页文字
     *    @ next_text 下一页文字
     * **/
    static function byTotalRows($nowPage,$totalRows,$showRows = 15,$params = []){
        if($totalRows <= 0){
            return [];
        }

        $totalPages = ceil($totalRows / $showRows);

        return self::byTotalPages($nowPage,$totalPages,$params);
    }
}