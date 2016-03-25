<?php

namespace NordCode\RoboParameters\Serializer;

use Symfony\Component\Yaml\Dumper;

class YamlSerializer implements ParameterSerializerInterface
{
    /**
     * @var Dumper
     */
    private $yamlDumper;

    /**
     * @param Dumper $yamlDumper
     */
    public function __construct(Dumper $yamlDumper = null)
    {
        // @codeCoverageIgnoreStart
        if (!$yamlDumper && !class_exists(Dumper::class)) {
            throw new \RuntimeException('The YamlSerializer requires the symfony/yaml library');
        }
        // @codeCoverageIgnoreEnd

        $this->yamlDumper = $yamlDumper ?: new Dumper();
    }

    /**
     * @inheritDoc
     */
    public function serialize(array $parameters, $fileHeader = null)
    {
        $output = '';

        if ($fileHeader) {
            $output .= \NordCode\RoboParameters\wrap_lines($fileHeader, '# ') . "\n";
        }

        return $output . $this->yamlDumper->dump($parameters);
    }
}
