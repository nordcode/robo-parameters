<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\DotenvSerializer;

class DotenvSerializerTest extends SerializerTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new DotenvSerializer();
    }

    /**
     * @test
     */
    public function testSerializeOneLevel()
    {
        $expected = <<<EOL
# Hello World
HELLO=1
FOO=bar
SPHERE="space? spaaace!"

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(
                array(
                    'hello' => 1,
                    'FOO' => 'nope',
                    'sphere' => 'space? spaaace!',
                    'foo' => 'bar'
                ),
                'Hello World'
            )
        );
    }

    public function testCorrectInvalidKeys()
    {
        $expected = <<<EOL
_12_HELLO=world

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(array('12_hello' => 'world'))
        );
    }

    /**
     * @test
     */
    public function testSerializeTwoLevels()
    {
        $expected = <<<EOL
HELLO=world
SUB__FOO=bar

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(array('hello' => 'world', 'sub' => array('foo' => 'bar')))
        );
    }
}
