<?php

namespace NordCode\RoboParameters;

/**
 * Get the maximum depth an array has
 *
 * @param array $array
 * @return int
 */
function array_depth(array $array)
{
    $maxLevel = 1;

    $inspectDepth = function (array $a, $depth) use (&$inspectDepth, &$maxLevel) {
        if ($depth > $maxLevel) {
            $maxLevel = $depth;
        }

        foreach ($a as $item) {
            if (is_array($item)) {
                $inspectDepth($item, $depth + 1);
            }
        }
    };

    $inspectDepth($array, 1);

    return $maxLevel;
}

/**
 * Wrap each line of $string between $prefix and $suffix
 *
 * @param string $string
 * @param string $prefix
 * @param string $suffix
 * @return string
 */
function wrap_lines($string, $prefix, $suffix = '')
{
    if (empty($string)) {
        return '';
    }

    return $prefix . str_replace("\n", $suffix . "\n" . $prefix, $string) . $suffix;
}

/**
 * Access an array field via dot.notation
 * Returns null if the field was not found
 *
 * @param array $array
 * @param string $key
 * @return mixed
 */
function dot_access(array $array, $key)
{
    $subject = &$array;
    $path = explode('.', $key);
    while (($subKey = array_shift($path)) !== null) {
        if (isset($subject[$subKey])) {
            $subject = &$subject[$subKey];
        } else {
            return null;
        }
    }
    return $subject;
}
