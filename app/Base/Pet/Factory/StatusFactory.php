<?php

namespace App\Base\Pet\Factory;

use App\Base\Pet\Collection\StatusCollection;
use App\Base\Pet\Entity\Status;

class StatusFactory
{
    /**
     * @param array $data
     *
     * @return StatusCollection
     */
    public function createStatuses(array $data): StatusCollection
    {
        $statuses = new StatusCollection();

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            $Status = $this->createStatus($value);

            if ($Status) {
                $statuses->setStatus($Status->getId(), $Status);
            }
        }

        return $statuses;
    }

    /**
     * @param array $data
     *
     * @return Status|null
     */
    public function createStatus(array $data): ?Status
    {
        $id = $data['id'] ?? null;
        $name = $data['name'] ?? null;

        if ($id && $name) {
            return new Status($id, $name);
        }

        return null;
    }
}