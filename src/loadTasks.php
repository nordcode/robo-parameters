<?php

namespace NordCode\RoboParameters;

use NordCode\RoboParameters\Task\Parameters;
use NordCode\RoboParameters\Task\SymfonyParameters;

trait loadTasks
{
    /**
     * @param string $path
     * @param string|null $format
     * @return Parameters
     */
    protected function writeParameters($path, $format = null)
    {
        return new Parameters($path, $format);
    }

    /**
     * @param string $path
     * @param string $format
     * @param string $parametersDist
     * @param string $parametersDistFormat
     * @return SymfonyParameters
     */
    protected function writeSymfonyParameters(
        $path = 'app/config/parameters.yml',
        $format = Format::YAML,
        $parametersDist = 'app/config/parameters.yml.dist',
        $parametersDistFormat = Format::YAML
    ) {
        return new SymfonyParameters($path, $format, $parametersDist, $parametersDistFormat);
    }
}
