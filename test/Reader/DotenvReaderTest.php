<?php

namespace NordCode\RoboParameters\Test\Reader;

use NordCode\RoboParameters\Reader\DotenvReader;
use NordCode\RoboParameters\Reader\ParameterReaderInterface;
use NordCode\RoboParameters\Test\Stub\DotenvLoaderStub;

class DotenvReaderTest extends ReaderTestCase
{
    /**
     * @return ParameterReaderInterface
     */
    protected function getFixture()
    {
        return new DotenvReader(DotenvLoaderStub::class);
    }

    /**
     * @return string
     */
    protected function getTestFileContent()
    {
        // doesn't matter currently because the parsing is done in the dotenv library
        return '';
    }

    /**
     * @return array
     */
    protected function getExpectedResultArray()
    {
        return DotenvLoaderStub::$returns;
    }
}
