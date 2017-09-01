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

class loader
{
    private static $loader;

    public static function getLoader()
    {
        if(self::$loader !== null) return self::$loader;

        spl_autoload_register(['loader', 'loadClassLoader'], true, true);

        self::$loader = $loader = new \NaRest\Kernel\NaLoader();

        spl_autoload_unregister(['loader', 'loadClassLoader']);

        $projects = require_once VE_PATH.'Config/Projects.php';

        if(!empty($projects))
            foreach ($projects as $v) $loader->setPsr4($v['namespace'].'\\',[$v['path']]);

        $loader->register(true);

        $autoload = require_once VE_PATH.'Config/Autoload.php';

        if(!empty($autoload))
            foreach ($autoload as $v) autoloadFiles(md5($v),$v);

        return $loader;
    }

    public static function loadClassLoader($class)
    {
        if ('NaRest\Kernel\NaLoader' === $class) {
            require VE_PATH."Kernel/NaLoader.php";
        }
    }
}

function autoloadFiles($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}

return loader::getLoader();