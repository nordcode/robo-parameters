<?php

namespace NordCode\RoboParameters;

use NordCode\RoboParameters\Reader\ReaderRegistry;
use Robo\Exception\TaskException;

trait FileReader
{
    /**
     * @var ReaderRegistry
     */
    protected $readerRegistry;

    /**
     * @param string $path
     * @param null|int $format
     * @return array
     * @throws TaskException
     */
    protected function readFromFile($path, $format = null)
    {
        if (is_string($path)) {
            if (!file_exists($path) || !is_file($path)) {
                throw new TaskException($this, 'Cannot open file ' . $path);
            }

            $format = $format ?: Format::guessFormatFromPath($path);

            $reader = $this->getReaderRegistry()->getInstanceForFormat($format);
            return $reader->readFromFile($path);
        } else {
            return array();
        }
    }

    /**
     * @return ReaderRegistry
     */
    public function getReaderRegistry()
    {
        if (!$this->readerRegistry) {
            $this->readerRegistry = ReaderRegistry::getDefaultInstance();
        }

        return $this->readerRegistry;
    }

    /**
     * @param ReaderRegistry $readerRegistry
     * @return $this
     */
    public function setReaderRegistry($readerRegistry)
    {
        $this->readerRegistry = $readerRegistry;
        return $this;
    }
}
