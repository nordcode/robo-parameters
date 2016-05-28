<?php

namespace NordCode\RoboParameters\Reader;

use NordCode\RoboParameters\Reader\Dotenv\DotenvLoaderInterface;

class DotenvReader implements ParameterReaderInterface
{
    /**
     * @var string
     */
    private $dotenvLoaderClass;

    /**
     * @param string $dotenvLoaderClass
     */
    public function __construct($dotenvLoaderClass = null)
    {
        // @codeCoverageIgnoreStart
        if ($dotenvLoaderClass === null && !class_exists('Dotenv\Loader')) {
            throw new \RuntimeException('The DotenvReader requires the vlucas/phpdotenv library');
            // @codeCoverageIgnoreEnd
        } elseif ($dotenvLoaderClass !== null && !is_subclass_of($dotenvLoaderClass, DotenvLoaderInterface::class)) {
            throw new \RuntimeException(
                'The loader ' . $dotenvLoaderClass . ' needs to implement ' . DotenvLoaderInterface::class
            );
        }

        $this->dotenvLoaderClass = $dotenvLoaderClass ?: 'NordCode\RoboParameters\Reader\Dotenv\FileLoader';
    }

    /**
     * @inheritDoc
     */
    public function readFromFile($path)
    {
        return $this->getLoaderForFile($path)->parse();
    }

    /**
     * @param string $path
     * @return DotenvLoaderInterface
     */
    private function getLoaderForFile($path)
    {
        return new $this->dotenvLoaderClass($path);
    }
}
