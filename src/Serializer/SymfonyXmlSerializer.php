<?php

namespace NordCode\RoboParameters\Serializer;

class SymfonyXmlSerializer extends XmlSerializer
{
    const CONTAINER_NAMESPACE = 'http://symfony.com/schema/dic/services';

    /**
     * @inheritDoc
     */
    protected function createDocumentElement(\DOMDocument $dom)
    {
        $documentElement = $dom->createElementNS(self::CONTAINER_NAMESPACE, 'container');
        $dom->appendChild($documentElement);
        $parameters = $dom->createElement('parameters');
        $documentElement->appendChild($parameters);
        return $parameters;
    }

    /**
     * @inheritDoc
     */
    protected function dumpArrayToNode(array $parameters, \DOMElement $parentNode, \DOMDocument $dom)
    {
        $isNumericalArray = $this->hasOnlyNumericalIndices($parameters);

        if ($isNumericalArray) {
            $parentNode->setAttribute('type', 'collection');
        }

        foreach ($parameters as $key => $parameter) {
            $node = $dom->createElement('parameter');
            if (!$isNumericalArray) {
                $node->setAttribute('key', $key);
            }
            $parentNode->appendChild($node);
            if (is_array($parameter)) {
                $this->dumpArrayToNode($parameter, $node, $dom);
            } else {
                $node->appendChild(new \DOMText($parameter));
            }
        }
    }

    /**
     * @param array $array
     * @return bool
     */
    private function hasOnlyNumericalIndices(array $array)
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
