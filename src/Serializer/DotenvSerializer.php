<?php

namespace NordCode\RoboParameters\Serializer;

class DotenvSerializer implements ParameterSerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $parameters, $fileHeader = null)
    {
        $output = '';
        if ($fileHeader) {
            $output .= \NordCode\RoboParameters\wrap_lines($fileHeader, '# ') . "\n";
        }

        return $output . $this->serializeKeyValuePairs($parameters);
    }

    /**
     * @param array $fields
     * @param string $keyPrefix
     * @return string
     */
    private function serializeKeyValuePairs(array $fields, $keyPrefix = '')
    {
        // unify the array by sanitized key so keys 'test' and 'TEST' are the same
        $unified = [];
        foreach ($fields as $key => $value) {
            $key = $this->sanitizeEnvVariableName($key);
            $unified[$key] = $value;
        }

        $output = '';
        foreach ($unified as $key => $value) {
            if (strlen($keyPrefix)) {
                $key = $keyPrefix . '__' . $key;
            }

            if (is_array($value)) {
                $output .= $this->serializeKeyValuePairs($value, $key);
            } elseif ($this->needsQuoting($value)) {
                $output .= sprintf('%s="%s"', $key, $value) . "\n";
            } else {
                $output .= sprintf('%s=%s', $key, $value) . "\n";
            }
        }
        return $output;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function needsQuoting($value)
    {
        // strings with spaces or interpolated variables better get quoted
        return is_string($value) && (strpos($value, ' ') !== false || strpos($value, '$') !== false);
    }

    /**
     * @see http://pubs.opengroup.org/onlinepubs/000095399/basedefs/xbd_chap08.html
     * @param string $key
     * @return string
     */
    private function sanitizeEnvVariableName($key)
    {
        // remove any non-allowed character
        $key = preg_replace('/[^\w]+/i', '', $key);
        if (!strlen($key)) {
            return '';
        }

        // check if the first character is non-numeric. Prefix with underscore otherwise
        if (!ctype_alpha($key[0])) {
            $key = '_' . $key;
        }

        return strtoupper($key);
    }
}
