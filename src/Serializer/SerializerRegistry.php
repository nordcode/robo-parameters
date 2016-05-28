<?php

namespace NordCode\RoboParameters\Serializer;

use NordCode\RoboParameters\Format;
use NordCode\RoboParameters\FormatRegistry;

/**
 * @method ParameterSerializerInterface getInstanceForFormat(string $format)
 */
class SerializerRegistry extends FormatRegistry
{
    /**
     * @return SerializerRegistry
     */
    public static function getDefaultInstance()
    {
        $instance = new self;
        $instance
            ->register(IniSerializer::class, array(Format::INI))
            ->register(JsonSerializer::class, array(Format::JSON))
            ->register(PhpSerializer::class, array(Format::PHP))
            ->register(XmlSerializer::class, array(Format::XML))
            ->register(YamlSerializer::class, array(Format::YAML))
            ->register(DotenvSerializer::class, array(Format::ENV));
        return $instance;
    }
}
