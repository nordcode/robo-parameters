<?php


namespace NordCode\RoboParameters\Task;

use NordCode\RoboParameters\Format;
use NordCode\RoboParameters\Reader\SymfonyXmlReader;
use NordCode\RoboParameters\Serializer\SymfonyXmlSerializer;

class SymfonyParameters extends Parameters
{
    /**
     * @param string $path
     * @param null|string $format
     * @param string $parametersDist
     * @param string $parametersDistFormat
     */
    public function __construct(
        $path = 'app/config/parameters.yml',
        $format = Format::YAML,
        $parametersDist = 'app/config/parameters.yml.dist',
        $parametersDistFormat = Format::YAML
    ) {
        parent::__construct($path, $format);
        $this->useBoilerplate($parametersDist, $parametersDistFormat);
        $this->getReaderRegistry()->register(SymfonyXmlReader::class, array(Format::XML));
        $this->getSerializerRegistry()->register(SymfonyXmlSerializer::class, array(Format::XML));
    }

    /**
     * {@inheritDoc}
     */
    protected function getParameters()
    {
        return array('parameters' => parent::getParameters());
    }

    /**
     * {@inheritDoc}
     */
    protected function readFromBoilerplate()
    {
        $parameters = parent::readFromBoilerplate();

        if (isset($parameters['parameters'])) {
            return $parameters['parameters'];
        } else {
            return $parameters;
        }
    }
}
