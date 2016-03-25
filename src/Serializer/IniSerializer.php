<?php

namespace NordCode\RoboParameters\Serializer;

class IniSerializer implements ParameterSerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $parameters, $fileHeader = null)
    {
        $output = '';
        if ($fileHeader) {
            $output .= \NordCode\RoboParameters\wrap_lines($fileHeader, '; ') . "\n";
        }

        // we can only make assumptions by the array-depth on how the output should look like
        // one level means that it is a simple key=value list
        // two levels mean that we have groups with key=value lists
        // more than two levels will be groups with key=value lists where the keys will be dot. notation for third
        //  and following levels
        $levels = \NordCode\RoboParameters\array_depth($parameters);

        if ($levels === 1) {
            return $output . $this->serializeKeyValuePairs($parameters);
        } else {
            return $output . $this->serializeWithGroups($parameters);
        }
    }

    /**
     * @param array $fields
     * @param string $keyPrefix
     * @return string
     */
    private function serializeKeyValuePairs(array $fields, $keyPrefix = '')
    {
        $output = '';
        foreach ($fields as $key => $value) {
            if (strlen($keyPrefix)) {
                $key = $keyPrefix . '.' . $key;
            }

            if (is_array($value)) {
                $output .= $this->serializeKeyValuePairs($value, $key);
            } else {
                $output .= sprintf("%s=%s\n", $key, $value);
            }
        }
        return $output;
    }

    /**
     * @param array $groups
     * @return string
     */
    private function serializeWithGroups(array $groups)
    {
        $output = '';
        $globalGroup = array();

        foreach ($groups as $group => $fields) {
            if (is_array($fields)) {
                if ($group) {
                    $output .= sprintf("[%s]\n", $group);
                }

                $output .= $this->serializeKeyValuePairs($fields);
            } else {
                $globalGroup[$group] = $fields;
            }
        }

        return $this->serializeKeyValuePairs($globalGroup) . $output;
    }
}
