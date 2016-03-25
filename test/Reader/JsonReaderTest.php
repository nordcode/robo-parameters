<?php

namespace NordCode\RoboParameters\Test\Reader;

use NordCode\RoboParameters\Reader\JsonReader;

class JsonReaderTest extends ReaderTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new JsonReader();
    }

    /**
     * @inheritDoc
     */
    protected function getTestFileContent()
    {
        return <<<EOL
{
    "hello": "world",
    "foo": "bar",
    "nested": ["a", "b"]
}
EOL;
    }

    /**
     * @inheritDoc
     */
    protected function getExpectedResultArray()
    {
        return array(
            'hello' => 'world',
            'foo' => 'bar',
            'nested' => array('a', 'b')
        );
    }
}
