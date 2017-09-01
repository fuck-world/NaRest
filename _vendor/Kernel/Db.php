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

class Db
{

    private $db = NULL ;

    private $config = [] , $database = '';


    public function connect($project = 'default')
    {

        $this->database = $project?:'default';

        if(!empty($this->db[$this->database])) return $this->db[$this->database];

        $this->config = Config()->get($this->database,'Databases');

        $pdo = new \PDO("mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['name']};charset={$this->config['charset']};", $this->config['user'], $this->config['pass']);

        $db = $this->db[$this->database] = new \NaRest\Drives\Db\LessQL\Database($pdo);

        $db->setRewrite( function( $table ) {
            return $this->config['table_prefix'] . $table;
        });

        return $db;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->db[$this->database],$name],$arguments);
    }
}