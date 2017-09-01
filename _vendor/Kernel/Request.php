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

class Request
{

    private $project = [] , $config = [],$projectConfig = [];

    public function __construct()
    {
        $this->project = Config()->get($this->getProjectName(),'Projects');
        $this->config  = Config()->get();
        $this->projectConfig = Config($this->getProjectName())->get();
    }

    public function getNamespace()
    {
        $uri     = $this->getRuleUri();

        if(empty($uri)) return NULL;
        
        $action  = ltrim(str_replace("/{$this->getActionName()}",'',$uri),'/');

        if(empty($action) && $this->project['format'] == 'html')
            $action = $this->getClassName();

        if(strstr($action,'/'))
            $action = implode("\\",array_map('ucfirst',explode('/',$action)));
        
        return
            "\\".ucfirst($this->project['namespace']).
            "\\".ucfirst($this->project['appDirectory']).
            "\\".ucfirst($action);
    }

    public function getProjectName()
    {
        $scriptName = str_replace(NA_ROOT,'',Headers()->get('SCRIPT_FILENAME'));
        return rtrim(str_replace(basename($scriptName),'',$scriptName),'/');
    }

    public function getActionName()
    {
        $action = basename($this->getRuleUri());

        if($this->project['format'] == 'html' && empty($action))
            $action = $this->projectConfig['default_action'];
        return $action;
    }

    public function getClassName()
    {
        $class = ucfirst(basename(str_replace('/'.$this->getActionName(),'',$this->getRuleUri())));

        if($this->project['format'] == 'html' && empty($class))
            $class = $this->projectConfig['default_controller'];

        return $class;
    }

    private function getRuleUri()
    {
        $routeRule = strtoupper($this->config['route_rule']);
        if($routeRule == 'PHP_SELF')
            return Headers()->get($routeRule);
        return rtrim(str_replace(Headers()->get('QUERY_STRING'),'',Headers()->get($this->config['route_rule'])),'?');
    }
}