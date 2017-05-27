<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\PhpSerializer;

class PhpSerializerTest extends SerializerTestCase
{
    /**
     * @inheritDoc
     */
    protected function getFixture()
    {
        return new PhpSerializer();
    }

    /**
     * @test
     */
    public function testSerialize()
    {
        $expected = <<<EOL
<?php
/**
 * Hello World
 */
return array (
  'a' => 'b',
  'c' => 'd',
);
EOL;

        $this->assertEquals($expected, $this->fixture->serialize(array('a' => 'b', 'c' => 'd'), 'Hello World'));
    }
}
