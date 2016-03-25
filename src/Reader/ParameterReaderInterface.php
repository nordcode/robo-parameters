<?php

namespace NordCode\RoboParameters\Reader;

interface ParameterReaderInterface
{
    /**
     * Read the parameter file and get the content of it as array
     * You can be sure the file exists so no need to check it twice
     *
     * @param string $path
     * @return array
     */
    public function readFromFile($path);
}
