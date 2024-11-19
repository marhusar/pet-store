<?php

declare(strict_types=1);

namespace App\Base\Pet\Repository;

use App\Base\Common\Collection\DataCollection;
use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Entity\Simple\PetRequest;

interface PetRepository
{

    /**
     * @param int $id
     *
     * @return DataCollection|null
     */
    public function findById(int $id): ?DataCollection;

    /**
     * @param int        $id
     * @param PetRequest $pet
     *
     * @return DataCollection
     */
    public function updatePet(int $id, PetRequest $pet): DataCollection;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteById(int $id): bool;

    /**
     * @param int $statusId
     *
     * @return DataCollection
     */
    public function findByStatus(int $statusId): DataCollection;

    /**
     * @param PetRequest $pet
     *
     * @return DataCollection
     */
    public function storePet(PetRequest $pet): DataCollection;

    /**
     * @param TagCollection $tags
     *
     * @return DataCollection
     */
    public function findByTags(TagCollection $tags): DataCollection;

    /**
     * @param int $id
     *
     * @return DataCollection
     */
    public function getById(int $id): DataCollection;

    /**
     * @param int      $offset
     * @param int|null $limit
     *
     * @return DataCollection
     */
    public function find(int $offset = 0, ?int $limit = null): DataCollection;
}