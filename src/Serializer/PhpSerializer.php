<?php

namespace NordCode\RoboParameters\Serializer;

class PhpSerializer implements ParameterSerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $parameters, $fileHeader = null)
    {
        $output = '<?php' . "\n";
        if ($fileHeader) {
            $commentContent = \NordCode\RoboParameters\wrap_lines($fileHeader, ' * ');
            $output .= "/**\n{$commentContent}\n */\n";
        }
        return $output . 'return ' . var_export($parameters, true) . ';';
    }
}
