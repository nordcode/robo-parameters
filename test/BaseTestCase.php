<?php

namespace NordCode\RoboParameters\Test;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Should only be used in cases where the protected methods are exposed as "public API" for child classes
     * that have no concrete implementation in this library
     *
     * @param object $object
     * @param string $method
     * @param array $args
     * @return mixed
     */
    protected function callProtectedMethod($object, $method, array $args = [])
    {
        $class = new \ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
