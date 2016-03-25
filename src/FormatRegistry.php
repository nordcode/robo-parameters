<?php

namespace NordCode\RoboParameters;

use NordCode\RoboParameters\Exception\InvalidArgumentException;

class FormatRegistry
{
    /**
     * @var array
     */
    protected $elements = array();

    /**
     * @param string $className
     * @param array $formats
     * @return $this
     */
    public function register($className, array $formats)
    {
        foreach ($formats as $format) {
            $this->elements[$format] = $className;
        }

        return $this;
    }

    /**
     * @param string $format
     * @return object
     */
    public function getInstanceForFormat($format)
    {
        if (!isset($this->elements[$format])) {
            throw new InvalidArgumentException("No handler available for format $format");
        }

        return new $this->elements[$format];
    }
}
