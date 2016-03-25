<?php

namespace NordCode\RoboParameters\Serializer;

class XmlSerializer implements ParameterSerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $parameters, $fileHeader = null)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        if ($fileHeader) {
            $comment = \NordCode\RoboParameters\wrap_lines($fileHeader, ' ~ ');
            $commentNode = $dom->createComment("\n{$comment}\n");
            $dom->appendChild($commentNode);
        }

        $documentElement = $this->createDocumentElement($dom);

        $this->dumpArrayToNode($parameters, $documentElement, $dom);

        return $dom->saveXML();
    }

    /**
     * @param \DOMDocument $dom
     * @return \DOMElement
     */
    protected function createDocumentElement(\DOMDocument $dom)
    {
        $documentElement = $dom->createElement('parameters');
        $dom->appendChild($documentElement);
        return $documentElement;
    }

    /**
     * @param array $parameters
     * @param \DOMElement $parentNode
     * @param \DOMDocument $dom
     */
    protected function dumpArrayToNode(array $parameters, \DOMElement $parentNode, \DOMDocument $dom)
    {
        foreach ($parameters as $key => $parameter) {
            $node = $dom->createElement($key);
            $parentNode->appendChild($node);
            if (is_array($parameter)) {
                $this->dumpArrayToNode($parameter, $node, $dom);
            } else {
                $node->appendChild(new \DOMText($parameter));
            }
        }
    }
}
