<?php

declare(strict_types=1);

namespace App\Base\Pet\Repository;

use App\Base\Common\Collection\DataCollection;
use App\Base\Common\Repository\EntityMapper;
use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Entity\Simple\PetRequest;


final class EagerPetRepository implements PetRepository
{
    /**
     * @var PetRepository
     */
    private PetRepository $petRepository;

    /**
     * @var EntityMapper
     */
    private EntityMapper $entityMapper;

    /**
     * @param PetRepository $petRepository
     * @param EntityMapper  $entityMapper
     */
    public function __construct(PetRepository $petRepository, EntityMapper $entityMapper)
    {
        $this->petRepository = $petRepository;
        $this->entityMapper  = $entityMapper;
    }

    /**
     * @param int $id
     *
     * @return DataCollection|null
     */
    public function findById(int $id): ?DataCollection
    {
        $pet = $this->petRepository->findById($id);

        if (!$pet) {
            return null;
        }

        return $this->entityMapper->loadReferences($pet);
    }

    /**
     * @param int $id
     *
     * @return DataCollection
     */
    public function getById(int $id): DataCollection
    {
        return $this->entityMapper->loadReferences($this->petRepository->getById($id));
    }

    /**
     * @param int $statusId
     *
     * @return DataCollection
     */
    public function findByStatus(int $statusId): DataCollection
    {
        $result = new DataCollection();
        $pets = $this->petRepository->findByStatus($statusId);

        foreach ($pets->all() as $pet) {
            $result->push($this->entityMapper->loadReferences($pet));
        }

        return $result;
    }

    /**
     * @param TagCollection $tags
     *
     * @return DataCollection
     */
    public function findByTags(TagCollection $tags): DataCollection
    {
        $result = new DataCollection();
        $pets = $this->petRepository->findByTags($tags);

        foreach ($pets->all() as $pet) {
            $result->push($this->entityMapper->loadReferences($pet));
        }

        return $result;
    }

    /**
     * @param PetRequest $pet
     *
     * @return DataCollection
     */
    public function storePet(PetRequest $pet): DataCollection
    {
        return $this->entityMapper->loadReferences($this->petRepository->storePet($pet));
    }

    /**
     * @param int        $id
     * @param PetRequest $pet
     *
     * @return DataCollection
     */
    public function updatePet(int $id, PetRequest $pet): DataCollection
    {
        return $this->entityMapper->loadReferences($this->petRepository->updatePet($id, $pet));
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->petRepository->deleteById($id);
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
        $pets = $this->petRepository->find($offset, $limit);

        foreach ($pets->all() as $pet) {
            $result->push($this->entityMapper->loadReferences($pet));
        }

        return $result;
    }
}