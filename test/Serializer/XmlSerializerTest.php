<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\XmlSerializer;

class XmlSerializerTest extends SerializerTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new XmlSerializer();
    }

    /**
     * @test
     */
    public function testSerialize()
    {
        $expected = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<!--
 ~ Hello World
-->
<parameters>
  <a>b</a>
  <c>d</c>
  <nested>
    <foo>bar</foo>
  </nested>
</parameters>

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(array('a' => 'b', 'c' => 'd', 'nested' => array('foo' => 'bar')), 'Hello World')
        );
    }
}
