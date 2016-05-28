<?php

namespace NordCode\RoboParameters\Test\Stub;

use NordCode\RoboParameters\Reader\Dotenv\DotenvLoaderInterface;

class DotenvLoaderStub implements DotenvLoaderInterface
{
    public static $returns = array(
        'FOO' => 'bar'
    );

    public function parse()
    {
        return self::$returns;
    }
}
