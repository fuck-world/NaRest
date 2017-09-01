<?php
namespace PrototypeApi\App;

use PrototypeApi\Core\Base;

class Test extends Base
{
    public function eee()
    {
        Response()->json('error',111);

        return ['x','x'];
    }
}