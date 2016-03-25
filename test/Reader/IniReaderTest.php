<?php

namespace NordCode\RoboParameters\Test\Reader;

use NordCode\RoboParameters\Reader\IniReader;

class IniReaderTest extends ReaderTestCase
{

    /**
     * {@inheritDoc}
     */
    protected function getFixture()
    {
        return new IniReader();
    }

    /**
     * {@inheritDoc}
     */
    protected function getTestFileContent()
    {
        return <<<EOL
foo = bar
bar.baz = 123
[section]
hello = world
EOL;
    }

    /**
     * {@inheritDoc}
     */
    protected function getExpectedResultArray()
    {
        return array(
            'foo' => 'bar',
            'bar.baz' => '123',
            'section' => array(
                'hello' => 'world'
            )
        );
    }
}
