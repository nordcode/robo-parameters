<?php

namespace NordCode\RoboParameters;

use NordCode\RoboParameters\Exception\FormatException;

class Format
{
    const YAML = 'yaml';
    const XML = 'xml';
    const INI = 'ini';
    const JSON = 'json';
    const PHP = 'php';

    /**
     * Map file extensions to internal file types
     *
     * @var array
     */
    private static $extensionMapping = array(
        'yml' => self::YAML,
        'yaml' => self::YAML,
        'xml' => self::XML,
        'ini' => self::INI,
        'php' => self::PHP,
        'json' => self::JSON
    );

    /**
     * @param string $path
     * @return string
     * @throws FormatException
     */
    public static function guessFormatFromPath($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (isset(self::$extensionMapping[$ext])) {
            return self::$extensionMapping[$ext];
        } else {
            throw new FormatException('Could not guess output format from path ' . $path);
        }
    }
}
