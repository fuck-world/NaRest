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

class Input
{
    public function get($key = NULL)
    {
        if(empty($key)) return $_GET;elseif(isset($_GET[$key]))return $_GET[$key];else return NULL;
    }

    public function post($key = NULL)
    {
        if(empty($key)) return $_POST;elseif(isset($_POST[$key]))return $_POST[$key];else return NULL;
    }

    public function any($key = NULL)
    {
        if($input = $this->get($key)) return $input;else return $this->post($key);
    }

    // @param boolean $proxy, [true|false], 是否优先获取从代理过来的地址
    // @return string

    public function ip($proxy = false)
    {
        if ($proxy) {
            $ip = empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? (empty($_SERVER["HTTP_CLIENT_IP"]) ? NULL : $_SERVER["HTTP_CLIENT_IP"]) : $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = empty($_SERVER["HTTP_CLIENT_IP"]) ? NULL : $_SERVER["HTTP_CLIENT_IP"];
        }

        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($p = strrpos($ip, ",")) {
            $ip = substr($ip, $p+1);
        }

        return trim($ip);
    }

    // @param bool   $restrict  是否进行严格的查检, 此方式为用正则对host进行匹配
    // @param string $allow       允许哪些 referer 过来请求
    // @return true / false       在允许的列表内返回true
    
    public function referer($restrict = true, $allow = '')
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : null;
        if (empty($referer)) { return true;    } /* 空的 referer 直接允许 */

        if ($restrict) {
            $url = parse_url($referer);
            if (empty($url['host'])) { return false; }
            $allow = '/'.str_replace('.', '\.', $allow).'/';
            return 0 < preg_match($allow, $url['host']);
        }

        return false !== strpos($referer, $allow);
    }

    /**
     * 判断请求是否是POST请求
     * @return bool
     */
    public function isPost()
    {
        return Headers()->get('REQUEST_METHOD') === 'POST';
    }
    
    /**
     * 判断请求是否是GET请求
     * @return bool
     */
    public function isGet()
    {
        return Headers()->get('REQUEST_METHOD') === 'GET';
    }
    
    /**
     * 判断请求是否是PUT请求
     * @return bool
     */
    public function isPut()
    {
        return Headers()->get('REQUEST_METHOD') === 'PUT';
    }
    
    /**
     * 判断请求是否是DELETE请求
     * @return bool
     */
    public function isDelete()
    {
        return Headers()->get('REQUEST_METHOD') === 'DELETE';
    }
    
    /**
     * 判断请求是否是OPTION请求
     * @return bool
     */
    public function isOption()
    {
        return Headers()->get('REQUEST_METHOD') === 'OPTION';
    }
    
    
    /**
     * 判断请求方法
     * @return mixed
     */
    public function method()
    {
        return Headers()->get('REQUEST_METHOD');
    }
    
    /**
     * 判断请求是否是AJAX并且POST请求
     * @return bool
     */
    public function isAjaxPost()
    {
        return $this->isPost() && $this->isAjax();
    }
    
    /**
     * 判断请求是否是AJAX并且GET请求
     * @return bool
     */
    public function isAjaxGet()
    {
        return $this->isGet() && $this->isAjax();
    }
    
    /**
     * 判断请求是否是AJAX请求
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}