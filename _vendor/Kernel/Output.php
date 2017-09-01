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

class Output
{

    private $project = [] , $projectConfig = [];

    public function __construct()
    {
        $this->project =  Config()->get(Request()->getProjectName(),'Projects');
        $this->projectConfig = Config(Request()->getProjectName())->get();
    }

    public function object2Array($object = NULL)
    {

        if(!empty($object) && is_array($object))
        {
            $arr = [];

            foreach ($object as $o) array_push($arr,$this->object2Array($o));

            return $arr;
        }
        elseif(is_object($object) && !empty($object))
            return json_decode(json_encode($object),true);
        else
            return [];
    }

    public function results($message = ''  , $code = 0 , $data  = [])
    {
        if(isset($this->project['format']) && $this->project['format'] == 'api')
            return call_user_func_array([Response(),'json'],[$message,$code,$data]);
        else
        {
            try
            {
                if($code <= 0)
                {
                    if($this->projectConfig['autoload_template'])
                        return View(implode('/',[Request()->getClassName(),Request()->getActionName()]))->assign($data)->display();
                    else
                        return false;
                }
                elseif($code > 0 && $code <= 10000 && $code != 300 )
                {
                    return View('Error/exception')->assign($data)->display();
                }
                else
                {
                    return View('Error/empty')->assign($data)->display();
                }

            }
            catch (\NaRest\Exception\ServerErrorException $e)
            {
                ErrorParse($e);
            }
        }
    }
}