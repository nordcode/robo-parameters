<?php

namespace NordCode\RoboParameters;

use NordCode\RoboParameters\Reader\ReaderRegistry;

trait FileConfigurable
{
    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @param string $path
     * @param int|null $format
     * @return $this
     */
    protected function loadConfiguration($path, $format = null)
    {
        $readerRegistry = ReaderRegistry::getDefaultInstance();
        $reader = $readerRegistry->getInstanceForFormat(
            $format ?: Format::guessFormatFromPath($path)
        );
        $this->configuration = $reader->readFromFile($path);
        return $this;
    }

    /**
     * Get a config value from the loaded configuration file
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function get($key, $default = null)
    {
        $val = dot_access($this->configuration, $key);
        return $val !== null ? $val : $default;
    }
}
