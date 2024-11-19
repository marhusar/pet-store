<?php

namespace App\Base\Pet\Repository;

use App\Base\Pet\Collection\StatusCollection;
use Exception;

interface StatusRepository
{
    /**
     * @param int $id
     *
     * @return string|null
     */
    public function findById(int $id): ?string;

    /**
     * @param string $name
     *
     * @return id|null
     */
    public function findByName(string $name): ?int;

    /**
     * @param string $name
     *
     * @return int
     * @throws Exception
     */
    public function getByName(string $name): int;

    /**
     *
     * @return StatusCollection
     */
    public function find(): StatusCollection;
}