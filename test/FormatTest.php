<?php

namespace NordCode\RoboParameters\Test;

use NordCode\RoboParameters\Format;

class FormatTest extends \PHPUnit_Framework_TestCase
{
    public function guessFormatFromPathDataProvider()
    {
        return array(
            array('/var/www/test/config.yaml', Format::YAML),
            array('app/config/parameters.yml.dist', Format::YAML),
            array('foo/.env', Format::ENV)
        );
    }

    /**
     * @test
     * @dataProvider guessFormatFromPathDataProvider
     */
    public function testGuessFormatFromPath($path, $format)
    {
        $this->assertEquals($format, Format::guessFormatFromPath($path));
    }

    /**
     * @test
     * @expectedException \NordCode\RoboParameters\Exception\FormatException
     */
    public function testGuessFormatFormPathFailsForUnknownFormat()
    {
        Format::guessFormatFromPath('nope');
    }
}
