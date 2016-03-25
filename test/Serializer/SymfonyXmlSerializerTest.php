<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\SymfonyXmlSerializer;

class SymfonyXmlSerializerTest extends SerializerTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new SymfonyXmlSerializer();
    }

    /**
     * @test
     */
    public function testSerialize()
    {
        $ns = SymfonyXmlSerializer::CONTAINER_NAMESPACE;
        $expected = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<!--
 ~ Hello World
-->
<container xmlns="{$ns}">
  <parameters>
    <parameter key="a">b</parameter>
    <parameter key="c">d</parameter>
    <parameter key="nested" type="collection">
      <parameter>foo</parameter>
      <parameter>bar</parameter>
    </parameter>
  </parameters>
</container>

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(array('a' => 'b', 'c' => 'd', 'nested' => array('foo', 'bar')), 'Hello World')
        );
    }
}
