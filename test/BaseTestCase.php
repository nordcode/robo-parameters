<?php

namespace NordCode\RoboParameters\Test;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
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

    /**
     * Because I'm too lazy to replace all getMock() usages...
     *
     * @param string $className
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMock($className, $methods = [])
    {
        return $this->getMockBuilder($className)->setMethods($methods)->getMock();
    }
}
