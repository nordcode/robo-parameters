<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\JsonSerializer;

class JsonSerializerTest extends SerializerTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new JsonSerializer();
    }

    /**
     * @test
     */
    public function testSerialize()
    {
        $expected = <<<EOL
{
    "a": "b",
    "c": "d"
}
EOL;

        $this->assertEquals($expected, $this->fixture->serialize(array('a' => 'b', 'c' => 'd'), 'Hallo Welt'));
    }
}
