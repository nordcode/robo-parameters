<?php

namespace NordCode\RoboParameters\Test\Serializer;

use NordCode\RoboParameters\Serializer\ParameterSerializerInterface;
use NordCode\RoboParameters\Test\BaseTestCase;

abstract class SerializerTestCase extends BaseTestCase
{
    /**
     * @var ParameterSerializerInterface
     */
    protected $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = $this->getFixture();
    }

    /**
     * @return ParameterSerializerInterface
     */
    abstract protected function getFixture();
}
