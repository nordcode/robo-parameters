<?php

namespace NordCode\RoboParameters\Task;

use NordCode\RoboParameters\Format;
use NordCode\RoboParameters\Reader\ReaderRegistry;
use NordCode\RoboParameters\Serializer\SerializerRegistry;
use Robo\Exception\TaskException;
use Robo\Result;
use Robo\Task\BaseTask;

/**
 * Task to load/set write parameters (from a file) to a file
 */
class Parameters extends BaseTask
{

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var ReaderRegistry
     */
    protected $readerRegistry;

    /**
     * @var SerializerRegistry
     */
    protected $serializerRegistry;

    /**
     * @var string
     */
    protected $outputPath;

    /**
     * @var string
     */
    protected $outputFormat;

    /**
     * @var string
     */
    protected $boilerplatePath;

    /**
     * @var string
     */
    protected $boilerplateFormat;

    /**
     * @var bool
     */
    protected $failOnMissingEnvVariables = false;

    /**
     * @var string
     */
    protected $fileHeader;

    /**
     * @var bool
     */
    protected $overrideExisting = false;

    /**
     * @var array
     */
    protected $loadFromEnvironment = array();

    /**
     * @var string
     */
    protected $envVariablePrefix = '';

    /**
     * Configure where the parameters will be written to
     *
     * @param string $path
     * @param null $format
     */
    public function __construct($path, $format = null)
    {
        $this->outputPath = $path;
        $this->outputFormat = $format;
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
     * @return Parameters
     */
    public function setReaderRegistry($readerRegistry)
    {
        $this->readerRegistry = $readerRegistry;
        return $this;
    }

    /**
     * @return SerializerRegistry
     */
    public function getSerializerRegistry()
    {
        if (!$this->serializerRegistry) {
            $this->serializerRegistry = SerializerRegistry::getDefaultInstance();
        }

        return $this->serializerRegistry;
    }

    /**
     * @param SerializerRegistry $serializerRegistry
     * @return Parameters
     */
    public function setSerializerRegistry($serializerRegistry)
    {
        $this->serializerRegistry = $serializerRegistry;
        return $this;
    }

    /**
     * Manually set a key/value pair
     * Optionally pass an whole array with key/value pairs
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $subkey => $value) {
                $this->set($subkey, $value);
            }
        } else {
            $this->parameters[$key] = $value;
        }
        return $this;
    }

    /**
     * Throw error when environment variables are missing
     *
     * @return $this
     */
    public function failOnMissingEnvVariables()
    {
        $this->failOnMissingEnvVariables = true;
        return $this;
    }

    /**
     * Lines that will be added as comment to the beginning of the output file
     *
     * @param array|string $header
     * @return $this
     */
    public function fileHeader($header)
    {
        $this->fileHeader = is_array($header) ? implode("\n", $header) : (string)$header;
        return $this;
    }

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
     * Override possible existing output file
     *
     * @return $this
     */
    public function overrideExisting()
    {
        $this->overrideExisting = true;
        return $this;
    }

    /**
     * Enable loading parameters from the environment
     *
     * @param array $names
     * @return $this
     */
    public function loadFromEnvironment(array $names = array())
    {
        $this->loadFromEnvironment = $names;
        return $this;
    }

    /**
     * Load environment variables with given prefix
     * Prefix will always be uppercased and followed by an underscore
     *
     * @param string $prefix
     * @return $this
     */
    public function envVariablePrefix($prefix)
    {
        $this->envVariablePrefix = $prefix;
        return $this;
    }

    /**
     * @return Result
     * @throws TaskException
     */
    public function run()
    {
        $format = $this->outputFormat ?: Format::guessFormatFromPath($this->outputPath);

        if (file_exists($this->outputPath) && !$this->overrideExisting) {
            throw new TaskException(
                $this,
                "The output file {$this->outputPath} does already exist. "
                . 'Enable ->overrideExisting() if you want to replace it'
            );
        }

        $parameters = $this->getParameters();

        $serializer = $this->getSerializerRegistry()->getInstanceForFormat($format);
        $outputString = $serializer->serialize($parameters, $this->fileHeader) ?: '';
        $success = @file_put_contents($this->outputPath, $outputString) !== false;

        if ($success) {
            return Result::success($this, 'Wrote parameters to ' . $this->outputPath);
        } else {
            $error = error_get_last();
            return Result::error(
                $this,
                sprintf("Something went wrong while trying to write to %s:\n%s", $this->outputPath, $error['message'])
            );
        }
    }

    /**
     * Load the parameters from the various sources and return the combined array
     *
     * @return array
     * @throws TaskException
     */
    protected function getParameters()
    {
        // 1. load from boilerplate
        // 2. load from environment
        // 3. load from $this->parameters
        $boilerplateParameters = $this->readFromBoilerplate();

        $environmentParameters = $this->tryToLoadFromEnvironment($this->loadFromEnvironment);
        $missingInEnvironment = array_diff($this->loadFromEnvironment, array_keys($environmentParameters));

        if ($missingInEnvironment && $this->failOnMissingEnvVariables) {
            throw new TaskException(
                $this,
                'Some parameters could not be found via environment: '
                . implode(', ', $this->getEnvName($missingInEnvironment))
            );
        }

        return array_replace_recursive($boilerplateParameters, $environmentParameters, $this->parameters);
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

    /**
     * Try to load the given variables from environment variables
     * Returns an array with names as keys and possible found values
     *
     * @param array $names
     * @return array
     */
    protected function tryToLoadFromEnvironment(array $names)
    {
        $ret = array();
        foreach ($names as $name) {
            $envName = $this->getEnvName($name);
            $value = getenv($envName);
            if ($value !== false) {
                $ret[$name] = $value;
            }
        }
        return $ret;
    }

    /**
     * Get the uppercase environment variable name(s) with prefix for the given values
     *
     * @param string|array $name
     * @return string|array
     */
    protected function getEnvName($name)
    {
        if (is_array($name)) {
            return array_map(array($this, __METHOD__), $name);
        } else {
            $prefix = $this->envVariablePrefix;
            if (!empty($prefix)) {
                $prefix .= '_';
            }

            return strtoupper($prefix . $name);
        }
    }
}
