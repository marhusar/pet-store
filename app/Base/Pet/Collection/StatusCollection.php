<?php

declare(strict_types=1);

namespace App\Base\Pet\Collection;

use App\Base\Common\Collection\Arrayable;
use App\Base\Common\Collection\Countable;
use App\Base\Pet\Entity\Status;
final class StatusCollection implements Arrayable, Countable
{
    /**
     * @var Status[]
     */
    private array $statuses = [];

    /**
     * @param array $statuses
     */
    public function __construct(array $statuses = [])
    {
        $this->addStatuses($statuses);
    }

    /**
     * @param Status $status
     *
     */
    public function addStatus(Status $status): void
    {
        $this->statuses[] = $status;
    }

    /**
     * @param array $statuses
     *
     */
    public function addStatuses(array $statuses): void
    {
        foreach($statuses as $status) {
            if ($status instanceof Status) {
                $this->addStatus($status);
            }
        }
    }

    /**
     * @param     $key
     * @param Status $status
     *
     */
    public function setStatus($key, Status $status): void
    {
        if (is_string($key) || is_int($key)) {
            $this->statuses[$key] = $status;
        }
    }

    /**
     * @param $key
     *
     * @return Status|null
     */
    public function findByKey($key): ?Status
    {
        if (is_string($key) || is_int($key)) {
            return $this->statuses[$key] ?? null;
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return Status|null
     */
    public function findByName(string $name): ?Status
    {
        foreach ($this->statuses as $status) {
            if ($name === $status->getName()) {
                return $status;
            }
        }

        return null;
    }

    /**
     * @param array $keys
     *
     * @return StatusCollection
     */
    public function findByKeys(array $keys): StatusCollection
    {
        $statuses = new StatusCollection();

        foreach ($keys as $key) {
            $status = $this->findByKey($key);

            if ($status) {
                $statuses->addStatus($status);
            }
        }

        return $statuses;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->statuses as $status) {
            $result[] = $status->toArray();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->statuses);
    }

    /**
     *
     * @return Status[]
     */
    public function all(): array
    {
        return $this->statuses;
    }

    /**
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}