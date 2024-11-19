<?php

namespace App\Base\Pet\Repository;

use App\Base\Common\Inflector;
use DOMElement;
use DOMDocument;
use DOMException;

class DocumentHandler
{
    /**
     * @var Inflector
     */
    private Inflector $singularizer;

    /**
     * @param Inflector $singularizer
     */
    public function __construct(Inflector $singularizer)
    {
        $this->singularizer = $singularizer;
    }

    /**
     * @param DOMElement $node
     *
     * @return array
     */
    public function transformToArray(DOMElement $node): array
    {
        $result = [];
        $elements = $node->childNodes;

        /** @var DOMElement $element */
        foreach ($elements as $key => $element) {
            $value =  $element->nodeValue;
            $name = $element->nodeName === $this->singularizer->singularize($node->nodeName) ? $key : $element->nodeName;

            if ($element->childElementCount > 0) {
                $value = $this->transformToArray($element);
            }

            if ($element->hasAttribute('type')) {
                $type = $element->getAttribute('type');
                if ($value === "") {
                    $value = ($type === "array") ? [] : ($type === "string" ? "" : null);
                } else {
                    settype($value, $type);
                }
            }

            $result[$name] = $value;
        }

        return $result;
    }

    /**
     * @param array       $items
     * @param DOMElement  $parent
     * @param DOMDocument $doc
     *
     * @return DOMElement
     * @throws DOMException
     */
    public function transformToElements(array $items, DOMElement $parent, DOMDocument $doc): DOMElement
    {
        foreach ($items as $name => $value) {
            if (is_array($value)) {
                $subParent = $doc->createElement($name);
                $subParent->setAttribute('type', 'array');
                $property = $this->transformToElements($value, $subParent, $doc);
            } else {
                $name = is_int($name) ? $this->singularizer->singularize($parent->nodeName) : $name;
                $property = $doc->createElement($name, (string) $value);
                $property->setAttribute('type', gettype($value));
            }

            $parent->appendChild($property);
        }

        return $parent;
    }

    /**
     * @param DOMElement $element
     * @param string[]   $except
     *
     * @return DOMElement
     */
    public function removeChildren(DOMElement $element, array $except = []): DOMElement
    {
        $childrenToRemove = [];

        foreach ($element->childNodes as $child) {
            if (!in_array($child->nodeName, $except)) {
                $childrenToRemove[] = $child;
            }
        }

        foreach ($childrenToRemove as $child) {
            $element->removeChild($child);
        }

        return $element;
    }

}