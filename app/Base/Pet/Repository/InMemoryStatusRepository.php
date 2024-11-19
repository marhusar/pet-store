<?php

namespace App\Base\Pet\Repository;

use App\Base\Pet\Collection\StatusCollection;
use Exception;

class InMemoryStatusRepository implements StatusRepository
{
    /**
     * @var StatusCollection
     */
    private StatusCollection $statuses;

    /**
     * @param StatusCollection $statuses
     */
    public function __construct(StatusCollection $statuses)
    {
        $this->statuses = $statuses;
    }

    /**
     * @param int $id
     *
     * @return string|null
     */
    public function findById(int $id): ?string
    {
        return $this->statuses->findByKey($id)?->getName();
    }


    /**
     * @param string $name
     *
     * @return int|null
     */
    public function findByName(string $name): ?int
    {
        return $this->statuses->findByName($name)?->getId();
    }

    /**
     * @param string $name
     *
     * @return int
     * @throws Exception
     */
    public function getByName(string $name): int
    {
        $statusId = $this->findByName($name);

        if (!$statusId) {
            throw new Exception('Invalid status name', 400);
        }

        return $statusId;
    }

    /**
     *
     * @return StatusCollection
     */
    public function find(): StatusCollection
    {
        return $this->statuses;
    }
}