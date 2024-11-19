<?php

namespace App\Base\Pet\Repository;

use App\Base\Pet\Collection\CategoryCollection;
use App\Base\Pet\Entity\Category;

interface CategoryRepository
{
    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function findById(int $id): ?Category;

    /**
     *
     * @return CategoryCollection
     */
    public function find(): CategoryCollection;
}