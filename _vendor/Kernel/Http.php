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

class Http
{

    private static $curlOptionString;

    /**
     * @param $url
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function get($url , $data = [] , $options = [])
    {
        if(empty($data)) return call_user_func_array([$this,'curl'],func_get_args());

        $url = strpos($url, '?') ? rtrim($url, '&') . '&' . http_build_query($data) : rtrim($url, '?') . '?' . http_build_query($data);

        return $this->curl($url , $data , $options);
    }

    /**
     * @param $url
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function post($url , $data = [] , $options = [])
    {
        return $this->curl( $url , $data , $options);
    }

    /**
     * @param $url
     * @param array $data
     * @param array $options
     * @return mixed
     */
    private function curl($url , $data = [] , $options = [])
    {
        if(empty($url)) return NULL;

        // 自动添加HTTP
        if(!preg_match('/^https?:\/\/(?:.*)/', $url)) {
            $url = 'http://' . $url;
        }

        // 处理$options
        if(is_array($options) && count($options)) {
            $options = array_change_key_case($options, CASE_UPPER);
            $tmp = [];
            foreach($options as $key => $option) {
                $tmp[str_replace('CURLOPT_', '', $key)] = $option;
            }
            $options = $tmp;
            unset($tmp);
        }

        // 初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // 设置HTTPHEADER
        if(!empty($options['HTTPHEADER'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $options['HTTPHEADER']);
        }

        // 设置超时时间 默认5秒
        $timeout = !empty($options['TIMEOUT']) ? (int)$options['TIMEOUT'] : 20;
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        // 设置只解析IPV4
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 处理dns秒级信号丢失问题
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);

        // 模拟浏览器标识
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36');

        // 设置COOKIE
        if(!empty($options['COOKIE'])) {
            curl_setopt($ch, CURLOPT_COOKIE, $options['COOKIE']);
        }

        // POST请求
        // 注意：这里默认GET请求的参数附带在URL里，如果直接使用http::curl()方法，并且传data参数，会触发POST请求
        if(!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        }

        // 设置头部
        if(!empty($options['HEADER'])) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        //暂无针对CURLINFO_HEADER_OUT的设置
        // 设置其他参数
        foreach($options as $option => $val) {
            if(!in_array($option, array('HEADER', 'COOKIE', 'TIMEOUT', 'HTTPHEADER'), true)) {
                $opt_defined = $this->getCurlOptionKey( 'CURLOPT_' . $option);
                //$opt_defined = constant('CURLOPT_' . $option); //basically same
                if($opt_defined !== null) {
                    curl_setopt($ch, $opt_defined, $val);
                }
            }
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 减少内存消耗，一个访问周期只调用一次
     * @param string $optionKey
     * @return mixed|null
     */
    private function getCurlOptionKey($optionKey)
    {
        if(static::$curlOptionString === null){
            static::$curlOptionString = implode(',', array_filter(array_keys(get_defined_constants(true)['curl']), function ($key) {
                    return strpos($key, 'CURLOPT_') === 0;
                })).',';
        }
        return strpos(static::$curlOptionString, $optionKey.',') !== false ? constant($optionKey) : null;
    }

    /**
     * 批量发GET送请求
     * @param $urls
     * @param array $options
     * @param null $callback
     * @return array
     */
    public function mutiGet($urls , $options = [], $callback = null)
    {
        // 组织数据
        foreach((array)$urls as $key => $url) {
            if(is_string($url)) {
                $urls[$key] = array(
                    'url' => $url,
                    'data' => null
                );
            }
        }
        return $this->mutiCurl($urls, $options, $callback);
    }

    /**
     * 批量发POST送请求
     * @param $requests
     * @param array $options
     * @param null $callback
     * @return array
     */
    public function mutiPost($urls , $options = [], $callback = null)
    {
        return $this->mutiCurl($urls , $options, $callback);
    }

    /**
     * @param $requests
     * @param array $options
     * @param null $callback
     * @param int $delay
     * @return array|null
     */
    public function mutiCurl($requests , $options = [] , $callback = null, $delay = 50)
    {
        if(empty($requests)) {
            return null;
        }

        // 处理$options
        if(is_array($options) && count($options)) {
            $options = array_change_key_case($options, CASE_UPPER);
            $tmp = [];
            foreach($options as $key => $option) {
                $tmp[str_replace('CURLOPT_', '', $key)] = $option;
            }
            $options = $tmp;
            unset($tmp);
        }

        $queue = curl_multi_init();
        $map = array();
        foreach ($requests as $id => $request) {
            $ch = curl_init();

            // 自动添加HTTP
            if(!preg_match('/^https?:\/\/(?:.*)/i', $request['url'])) {
                $request['url'] = 'http://' . $request['url'];
            }
            curl_setopt($ch, CURLOPT_URL, $request['url']);

            // 设置HTTPHEADER
            if(!empty($options['HTTPHEADER'])) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $options['HTTPHEADER']);
            }

            // 设置超时时间 默认5秒
            $timeout = !empty($options['TIMEOUT']) ? (int)$options['TIMEOUT'] : 5;
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            // 设置只解析IPV4
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36');

            // 设置COOKIE
            if(!empty($options['COOKIE'])) {
                curl_setopt($ch, CURLOPT_COOKIE, $options['COOKIE']);
            }

            // POST请求
            if(!empty($request['data'])) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);
            }

            // 设置头部
            if(!empty($options['HEADER'])) {
                curl_setopt($ch, CURLOPT_HEADER, true);
            }

            // 设置其他参数
            foreach($options as $option => $val) {
                if(!in_array($option, array('HEADER', 'COOKIE', 'TIMEOUT', 'HTTPHEADER'), true)) {
                    $opt_defined = $this->getCurlOptionKey( 'CURLOPT_' . $option);
                    if($opt_defined !== null) {
                        curl_setopt($ch, $opt_defined, $val);
                    }
                }
            }

            curl_multi_add_handle($queue, $ch);
            $map[(string) $ch] = $id;
        }

        $responses = array();
        do {
            while (($code = curl_multi_exec($queue, $active)) === CURLM_CALL_MULTI_PERFORM);
            if ($code !== CURLM_OK) {
                break;
            }

            // 找出当前已经完成的请求
            while ($done = curl_multi_info_read($queue)) {
                $data = curl_multi_getcontent($done['handle']);
                $id = $map[(string) $done['handle']];

                // 是否使用回调函数
                if($callback != null) {
                    // 异步立即处理当前请求
                    $ret = array(
                        'ret' => array(
                            'id' => $id,
                            'delay' => $delay,
                            'response' => $data,
                        )
                    );
                    call_user_func_array($callback, $ret);
                }
                // 移除已经处理完毕请求
                curl_multi_remove_handle($queue, $done['handle']);
                curl_close($done['handle']);

                $responses[$id] = $data;
            }
            if ($active > 0) {
                curl_multi_select($queue, 0.5);
            }

        } while ($active);

        curl_multi_close($queue);

        // 返回批量响应结果
        return $responses;
    }
}