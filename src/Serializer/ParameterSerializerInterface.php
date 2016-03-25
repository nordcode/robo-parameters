<?php

namespace NordCode\RoboParameters\Serializer;

interface ParameterSerializerInterface
{
    /**
     * Serialize the parameters to the desired format this class is responsible for
     * Optionally pass a block of text that will be added as comment to the beginning og the file
     *
     * @param array $parameters
     * @param string|null $fileHeader
     * @return string
     */
    public function serialize(array $parameters, $fileHeader = null);
}
