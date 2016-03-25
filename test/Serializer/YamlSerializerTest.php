<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\YamlSerializer;
use Symfony\Component\Yaml\Dumper;

class YamlSerializerTest extends SerializerTestCase
{
    const YAML_OUTPUT = 'foo';

    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        $yaml = $this->getMock(Dumper::class, array('dump'));
        $yaml->expects($this->any())->method('dump')->willReturn(self::YAML_OUTPUT);
        return new YamlSerializer($yaml);
    }

    /**
     * @test
     */
    public function testSerialize()
    {
        $expected = "# Hallo Welt\n" . self::YAML_OUTPUT;
        $this->assertEquals($expected, $this->fixture->serialize(array(), 'Hallo Welt'));
    }
}
