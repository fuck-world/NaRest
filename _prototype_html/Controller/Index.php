<?php

namespace  XdManager\Controller;

use NaRest\Util\Cookie;
use NaRest\Util\Url;
use \XdManager\Core\Base;
use \NaRest\Util\Page;

class Index extends Base
{
    
    public function home()
    {
        View('Common/main')->display();
    }
}