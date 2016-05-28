<?php

namespace NordCode\RoboParameters\Reader\Dotenv;

use Dotenv\Loader as OriginalLoader;

class FileLoader extends OriginalLoader implements DotenvLoaderInterface
{
    /**
     * Returns the environment variables from the file as associative array
     *
     * TODO find a way to decouple this from the dotenv library (without reinventing the wheel)
     *
     * @return array
     */
    public function parse()
    {
        $this->ensureFileIsReadable();
        $envVariables = array();

        $filePath = $this->filePath;
        $lines = $this->readLinesFromFile($filePath);
        foreach ($lines as $line) {
            if (!$this->isComment($line) && $this->looksLikeSetter($line)) {
                list($name, $value) = $this->normaliseEnvironmentVariable($line, null);
                $envVariables[$name] = $value;
            }
        }

        return $envVariables;
    }
}
