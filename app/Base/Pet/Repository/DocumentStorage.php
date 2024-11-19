<?php

namespace App\Base\Pet\Repository;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
class DocumentStorage
{
    /**
     * @var DOMDocument|null
     */
    private ?DOMDocument $document = null;

    /**
     * @var string
     */
    private string $documentPath;

    /**
     * @param string $documentPath
     */
    public function __construct(string $documentPath)
    {
        $this->documentPath = $documentPath;
    }

    /**
     *
     * @return DOMDocument
     */
    public function getStorage(): DOMDocument
    {
        if (!$this->document) {
            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
            $doc->load(getcwd() . $this->documentPath);
            $this->document = $doc;
        }

        return $this->document;
    }

    /**
     * @param string $expression
     *
     * @return DOMNodeList
     */
    public function query(string $expression): DOMNodeList
    {
        $doc = $this->getStorage();
        $xpath = new DOMXPath($doc);

        return $xpath->query($expression);
    }

    /**
     *
     */
    public function saveStorage(): void
    {
        $doc = $this->getStorage();
        $doc->save(getcwd() . $this->documentPath);
    }
}