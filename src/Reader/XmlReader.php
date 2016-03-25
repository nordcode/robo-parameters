<?php

namespace NordCode\RoboParameters\Reader;

/**
 * Reader for simple xml files where each node represents the final array-key
 */
class XmlReader implements ParameterReaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function readFromFile($path)
    {
        $content = file_get_contents($path);
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($content);

        return $this->nodesToArray($dom->documentElement->childNodes);
    }

    /**
     * @param \DOMNodeList $nodes
     * @return array
     */
    protected function nodesToArray(\DOMNodeList $nodes)
    {
        $ret = array();
        foreach ($nodes as $node) {
            if ($node instanceof \DOMComment) {
                continue;
            }

            $value = null;

            /** @var \DOMNode $node */
            if ($node->hasChildNodes()) {
                if ($node->childNodes->length === 1 && $node->childNodes->item(0) instanceof \DOMText) {
                    $value = $node->textContent;
                } else {
                    $value = $this->nodesToArray($node->childNodes);
                }
            }
            $ret[$node->localName] = $value;
        }
        return $ret;
    }
}
