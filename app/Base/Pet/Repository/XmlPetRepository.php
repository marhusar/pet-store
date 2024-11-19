<?php

declare(strict_types=1);

namespace App\Base\Pet\Repository;

use App\Base\Common\Collection\DataCollection;
use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Entity\Simple\PetRequest;
use DOMNodeList;
use DOMElement;
use Exception;
use DOMException;

final class XmlPetRepository implements PetRepository
{

    /**
     * @var DocumentStorage
     */
    private DocumentStorage $storage;

    /**
     * @var DocumentHandler
     */
    private DocumentHandler $documentHandler;

    /**
     * @param DocumentStorage $storage
     * @param DocumentHandler $documentHandler
     */
    public function __construct(DocumentStorage $storage, DocumentHandler $documentHandler)
    {
        $this->storage         = $storage;
        $this->documentHandler = $documentHandler;
    }

    /**
     * @param int        $id
     * @param PetRequest $pet
     *
     * @return DataCollection
     * @throws DOMException
     */
    public function updatePet(int $id, PetRequest $pet): DataCollection
    {
        $doc = $this->storage->getStorage();
        $entity = $this->findPetElementById($id);

        if (!$entity) {
            throw new Exception('Pet not found', 404);
        }

        $properties = $pet->toArray();
        $entity = $this->documentHandler->removeChildren($entity, ['id']);
        $entity = $this->documentHandler->transformToElements($properties, $entity, $doc);

        $this->storage->saveStorage();
        $result = $this->documentHandler->transformToArray($entity);

        return new DataCollection($result);
    }

    /**
     * @param PetRequest $pet
     *
     * @return DataCollection
     * @throws \DOMException
     */
    public function storePet(PetRequest $pet): DataCollection
    {
        $doc = $this->storage->getStorage();
        $pets = $doc->getElementsByTagName('pets')->item(0);
        $metadata = $pets->getElementsByTagName('metadata')->item(0);
        $autoIncrement = $metadata->getElementsByTagName('autoIncrement')->item(0);

        $properties = ['id' => (int) $autoIncrement->nodeValue] + $pet->toArray();
        $entity = $doc->createElement('pet');
        $entity = $this->documentHandler->transformToElements($properties, $entity, $doc);
        $pets->appendChild($entity);
        $autoIncrement->nodeValue = (string) ((int) $autoIncrement->nodeValue + 1);

        $this->storage->saveStorage();

        return new DataCollection($this->documentHandler->transformToArray($entity));
    }

    /**
     * @param int $id
     *
     * @return DOMElement|null
     */
    private function findPetElementById(int $id): ?DOMElement
    {
        $result = $this->storage->query("//pet[id=" . $id . "]");

        if ($result->count() === 0) {
            return null;
        }

        return $result->item(0);
    }

    /**
     * @param int      $offset
     * @param int|null $limit
     *
     * @return DOMNodeList|null
     */
    public function findPetElements(int $offset = 0, ?int $limit = null): ?DOMNodeList
    {
        $limit = $offset + $limit;
        $conditions = [];

        if ($offset !== null) {
            $conditions[] = 'position() > ' . $offset;
        }
        if ($limit) {
            $conditions[] = 'position() < ' . $limit;
        }

        $conditions = "//pet[" . implode(' and ', $conditions) . "]";

        return $this->storage->query($conditions);
    }

    /**
     * @param int      $offset
     * @param int|null $limit
     *
     * @return DataCollection
     */
    public function find(int $offset = 0, ?int $limit = null): DataCollection
    {
        $result = new DataCollection();
        $petElements = $this->findPetElements($offset, $limit);

        if ($petElements) {
            foreach ($petElements as $petElement) {
                $pet = new DataCollection($this->documentHandler->transformToArray($petElement));
                $result->push($pet);
            }
        }

        return $result;
    }

    /**
     * @param int $id
     *
     * @return DataCollection|null
     */
    public function findById(int $id): ?DataCollection
    {
        $element = $this->findPetElementById($id);

        if (!$element) {
            return null;
        }

        return new DataCollection($this->documentHandler->transformToArray($element));
    }

    /**
     * @param int $id
     *
     * @return DataCollection
     * @throws Exception
     */
    public function getById(int $id): DataCollection
    {
        $pet = $this->findById($id);

        if (!$pet) {
            throw new Exception('Pet not found', 404);
        }

        return $pet;
    }

    /**
     * @param TagCollection $tags
     *
     * @return DOMNodeList|null
     */
    public function findPetElementsByTags(TagCollection $tags): ?DOMNodeList
    {
        $conditions = [];

        if ($tags->isEmpty()) {
            return null;
        }

        foreach ($tags->all() as $tag) {
            $conditions[] = "tagIds/*='" . $tag->getId() . "'";
        }

        $conditions = "//pet[" . implode(' or ', $conditions) . "]";

        return $this->storage->query($conditions);
    }

    /**
     * @param TagCollection $tags
     *
     * @return DataCollection
     */
    public function findByTags(TagCollection $tags): DataCollection
    {
        $result = new DataCollection();
        $petElements = $this->findPetElementsByTags($tags);

        if ($petElements) {
            foreach ($petElements as $petElement) {
                $pet = new DataCollection($this->documentHandler->transformToArray($petElement));
                $result->push($pet);
            }
        }

        return $result;
    }

    /**
     * @param int $statusId
     *
     * @return DOMNodeList|null
     */
    public function findPetElementsByStatus(int $statusId): ?DOMNodeList
    {
        $conditions = "//pet[statusId=" . $statusId . "]";

        return $this->storage->query($conditions);
    }

    /**
     * @param int $statusId
     *
     * @return DataCollection
     */
    public function findByStatus(int $statusId): DataCollection
    {
        $result = new DataCollection();
        $petElements = $this->findPetElementsByStatus($statusId);

        if ($petElements) {
            foreach ($petElements as $petElement) {
                $pet = new DataCollection($this->documentHandler->transformToArray($petElement));
                $result->push($pet);
            }
        }

        return $result;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteById(int $id): bool
    {
        $petElement = $this->findPetElementById($id);

        if (!$petElement) {
            throw new Exception('Pet not found', 400);
        }

        $petElement->remove();
        $this->storage->saveStorage();

        return true;
    }
}