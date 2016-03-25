<?php

namespace NordCode\RoboParameters\Serializer;

class JsonSerializer implements ParameterSerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $parameters, $fileHeader = null)
    {
        return json_encode($parameters, JSON_PRETTY_PRINT);
    }
}
