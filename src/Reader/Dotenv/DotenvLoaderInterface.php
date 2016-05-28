<?php

namespace NordCode\RoboParameters\Reader\Dotenv;

/**
 * Custom dotenv loader that does not populate the read variables to environment
 */
interface DotenvLoaderInterface
{
    /**
     * Returns the environment variables from the file as associative array
     *
     * @return array
     */
    public function parse();
}
