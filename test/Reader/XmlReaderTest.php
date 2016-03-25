<?php

namespace NordCode\RoboParameters\Test\Reader;

use NordCode\RoboParameters\Reader\XmlReader;

class XmlReaderTest extends ReaderTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new XmlReader();
    }

    /**
     * @inheritDoc
     */
    protected function getTestFileContent()
    {
        return <<<EOL
<?xml version="1.0" encoding="UTF-8" ?>
<parameters>
    <key>Value</key>
    <!-- this is a comment -->
    <hello>Universe</hello>
    <hello>World</hello>
    <nested>
        <foo> untrimmed </foo>
    </nested>
</parameters>
EOL;
    }

    /**
     * @inheritDoc
     */
    protected function getExpectedResultArray()
    {
        return array(
            'key' => 'Value',
            'hello' => 'World',
            'nested' => array(
                'foo' => ' untrimmed '
            )
        );
    }
}
