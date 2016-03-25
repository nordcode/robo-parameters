<?php

namespace NordCode\RoboParameters\Test;

class functionsTest extends BaseTestCase
{

    public function test_array_depth_data()
    {
        return array(
            array(
                array(),
                1
            ),
            array(
                array(0 => array()),
                2
            ),
            array(
                array(0 => array(), 1 => array(array())),
                3
            ),
            array(
                array(0 => array(array()), 1 => array(array(array(0 => 123)))),
                4
            )
        );
    }

    /**
     * @test
     * @dataProvider test_array_depth_data
     * @param array $array
     * @param int $expected_depth
     */
    public function test_array_depth(array $array, $expected_depth)
    {
        $this->assertEquals($expected_depth, \NordCode\RoboParameters\array_depth($array));
    }

    public function test_wrap_lines_data()
    {
        return array(
            array(
                "",
                ">",
                ">",
                ""
            ),
            array(
                "hello world",
                "> ",
                " <",
                "> hello world <"
            ),
            array(
                "This is the first line\nThis is the second line",
                "<!-- ",
                " -->",
                "<!-- This is the first line -->\n<!-- This is the second line -->"
            )
        );
    }

    /**
     * @test
     * @dataProvider test_wrap_lines_data
     * @param array $array
     * @param int $expected_depth
     */
    public function test_wrap_lines($string, $prefix, $suffix, $expectedOutput)
    {
        $this->assertEquals($expectedOutput, \NordCode\RoboParameters\wrap_lines($string, $prefix, $suffix));
    }

    /**
     * @test
     */
    public function test_dot_access()
    {
        $subject = array(
            'foo' => array(
                'bar' => 'baz'
            ),
            'hello' => 'world'
        );
        $this->assertEquals('baz', \NordCode\RoboParameters\dot_access($subject, 'foo.bar'));
        $this->assertNull(\NordCode\RoboParameters\dot_access($subject, 'bar'));
    }
}
