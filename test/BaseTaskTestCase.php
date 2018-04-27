<?php

namespace NordCode\RoboParameters\Test;

use Robo\Robo;

abstract class BaseTaskTestCase extends BaseTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        Robo::createDefaultContainer();
        parent::setUpBeforeClass();
    }
}