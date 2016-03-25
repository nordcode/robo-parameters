<?php

namespace NordCode\RoboParameters\Reader;

use Symfony\Component\Yaml\Parser;

class YamlReader implements ParameterReaderInterface
{
    /**
     * @var Parser
     */
    private $yamlParser;

    /**
     * @param Parser $yamlParser
     */
    public function __construct(Parser $yamlParser = null)
    {
        // @codeCoverageIgnoreStart
        if (!$yamlParser && !class_exists(Parser::class)) {
            throw new \RuntimeException('The YamlReader requires the symfony/yaml library');
        }
        // @codeCoverageIgnoreEnd

        $this->yamlParser = $yamlParser ?: new Parser();
    }

    /**
     * {@inheritDoc}
     */
    public function readFromFile($path)
    {
        $content = file_get_contents($path);
        return $this->yamlParser->parse($content);
    }
}
