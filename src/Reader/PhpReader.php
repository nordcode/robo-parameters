<?php

namespace NordCode\RoboParameters\Reader;

class PhpReader implements ParameterReaderInterface
{
    /**
     * @inheritDoc
     */
    public function readFromFile($path)
    {
        return include $path;
    }
}
