<?php

namespace NordCode\RoboParameters;

use Robo\Exception\TaskException;

trait Boilerplate
{
    /**
     * @var string
     */
    protected $boilerplatePath;

    /**
     * @var string
     */
    protected $boilerplateFormat;

    /**
     * Configure from which file the basic values will be read from
     *
     * @param string $path
     * @param string|null $format
     * @return $this
     */
    public function useBoilerplate($path, $format = null)
    {
      $this->boilerplatePath = $path;
      $this->boilerplateFormat = $format;
      return $this;
    }

    /**
     * @return array
     * @throws TaskException
     */
    protected function readFromBoilerplate()
    {
      if (is_string($this->boilerplatePath)) {
        if (!file_exists($this->boilerplatePath) || !is_file($this->boilerplatePath)) {
          throw new TaskException($this, 'Cannot open boilerplate file ' . $this->boilerplatePath);
        }

        $format = $this->boilerplateFormat ?: Format::guessFormatFromPath($this->boilerplatePath);

        $reader = $this->getReaderRegistry()->getInstanceForFormat($format);
        return $reader->readFromFile($this->boilerplatePath);
      } else {
        return array();
      }
    }
}
