<?php

namespace NordCode\RoboParameters\Reader;

class JsonReader implements ParameterReaderInterface
{
    /**
     * @inheritDoc
     */
    public function readFromFile($path)
    {
        $content = file_get_contents($path);
        return $content ? json_decode($content, true) : array();
    }
}
