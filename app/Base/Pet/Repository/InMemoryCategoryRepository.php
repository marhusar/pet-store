<?php

namespace App\Base\Pet\Repository;

use App\Base\Pet\Collection\CategoryCollection;
use App\Base\Pet\Entity\Category;

class InMemoryCategoryRepository implements CategoryRepository
{
    /**
     * @var CategoryCollection
     */
    private CategoryCollection $categories;

    /**
     * @param CategoryCollection $categories
     */
    public function __construct(CategoryCollection $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @param int $id
     *
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return $this->categories->findByKey($id);
    }

    /**
     *
     * @return CategoryCollection
     */
    public function find(): CategoryCollection
    {
        return $this->categories;
    }
}