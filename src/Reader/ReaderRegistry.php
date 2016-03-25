<?php

namespace NordCode\RoboParameters\Reader;

use NordCode\RoboParameters\Format;
use NordCode\RoboParameters\FormatRegistry;

/**
 * @method ParameterReaderInterface getInstanceForFormat(string $format)
 */
class ReaderRegistry extends FormatRegistry
{
    /**
     * @return ReaderRegistry
     */
    public static function getDefaultInstance()
    {
        $instance = new self;
        $instance
            ->register(IniReader::class, array(Format::INI))
            ->register(JsonReader::class, array(Format::JSON))
            ->register(PhpReader::class, array(Format::PHP))
            ->register(XmlReader::class, array(Format::XML))
            ->register(YamlReader::class, array(Format::YAML));
        return $instance;
    }
}
