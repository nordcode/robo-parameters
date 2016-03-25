<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\IniSerializer;

class IniSerializerTest extends SerializerTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new IniSerializer();
    }

    /**
     * @test
     */
    public function testSerializeOneLevel()
    {
        $expected = <<<EOL
; Hallo Welt
hello=world
foo=bar

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(array('hello' => 'world', 'foo' => 'bar'), 'Hallo Welt')
        );
    }

    /**
     * @test
     */
    public function testSerializeTwoLevels()
    {
        $expected = <<<EOL
hello=world
[section]
foo=bar

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(array('hello' => 'world', 'section' => array('foo' => 'bar')))
        );
    }

    /**
     * @test
     */
    public function testSerializeMoreThanTwoLevels()
    {
        $expected = <<<EOL
hello=world
[section]
foo.test=a
foo.test2=b
foo.bar.baz=c

EOL;

        $this->assertEquals(
            $expected,
            $this->fixture->serialize(
                array(
                    'hello' => 'world',
                    'section' => array(
                        'foo' => array(
                            'test' => 'a',
                            'test2' => 'b',
                            'bar' => array(
                                'baz' => 'c'
                            )
                        )
                    )
                )
            )
        );
    }
}
