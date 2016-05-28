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
    const ENV = 'env';

    /**
     * Some apps come with suffixed example config files
     * Like Symfony's parameters.yml.dist or Dotenv's .env.example
     *
     * @var array
     */
    private static $distributionSuffixes = array(
        '.dist',
        '.example'
    );

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
        'json' => self::JSON,
        'env' => self::ENV
    );

    /**
     * @param string $path
     * @return string
     * @throws FormatException
     */
    public static function guessFormatFromPath($path)
    {
        $filename = self::stripDistSuffix(basename($path));
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (isset(self::$extensionMapping[$ext])) {
            return self::$extensionMapping[$ext];
        } else {
            throw new FormatException('Could not guess output format from path ' . $path);
        }
    }

    /**
     * @param string $filename
     * @return string
     */
    private static function stripDistSuffix($filename)
    {
        $escapedSuffixes = array_map('preg_quote', self::$distributionSuffixes);
        $regex = '/' . implode('|', $escapedSuffixes) . '$/';
        return preg_replace($regex, '', $filename) ?: $filename;
    }
}
