<?php

namespace NordCode\RoboParameters;

trait FileConfigurable
{
    // make sure they don't appear as tasks when imported into the Robo class
    use FileReader {
        getReaderRegistry as protected;
        setReaderRegistry as protected;
    }

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
        $this->configuration = $this->readFromFile($path, $format) + $this->configuration;

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
