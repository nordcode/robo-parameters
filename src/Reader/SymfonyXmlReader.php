<?php

namespace NordCode\RoboParameters\Reader;

/**
 * XML reader that should be compatible with the Symfony XML syntax
 * Note that the class has only basic support and things like constants or imports do not work
 */
class SymfonyXmlReader extends XmlReader
{
    /**
     * @param \DOMNodeList $nodes
     * @return array
     */
    protected function nodesToArray(\DOMNodeList $nodes)
    {
        $ret = array();
        foreach ($nodes as $node) {
            /** @var \DOMNode $node */
            $value = $this->nodeValue($node);

            if ($node->attributes->getNamedItem('key')) {
                $key = $node->attributes->getNamedItem('key')->textContent;
            } else {
                $key = $node->localName;
            }

            $ret[$key] = $value;
        }
        return $ret;
    }

    /**
     * @param \DOMNodeList $nodes
     * @return array
     */
    private function collectionToArray(\DOMNodeList $nodes)
    {
        $ret = array();
        foreach ($nodes as $node) {
            $ret[] = $this->nodeValue($node);
        }
        return $ret;
    }

    /**
     * @param \DOMNode $node
     * @return array|null|string
     */
    private function nodeValue(\DOMNode $node)
    {
        $typeAttribute = $node->attributes->getNamedItem('type');
        if ($typeAttribute && $typeAttribute->textContent === 'collection') {
            return $this->collectionToArray($node->childNodes);
        } elseif ($node->hasChildNodes()) {
            if ($node->childNodes->length === 1 && $node->childNodes->item(0) instanceof \DOMText) {
                return $node->textContent;
            } else {
                return $this->nodesToArray($node->childNodes);
            }
        }

        return null;
    }
}
