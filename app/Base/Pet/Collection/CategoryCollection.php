<?php

declare(strict_types=1);

namespace App\Base\Pet\Collection;

use App\Base\Common\Collection\Arrayable;
use App\Base\Common\Collection\Countable;
use App\Base\Pet\Entity\Category;

final class CategoryCollection implements Arrayable, Countable
{
    /**
     * @var Category[]
     */
    private array $categories = [];

    /**
     * @param array $categories
     */
    public function __construct(array $categories = [])
    {
        $this->addCategories($categories);
    }


    /**
     * @param Category $category
     *
     */
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }

    /**
     * @param          $key
     * @param Category $category
     *
     */
    public function setCategory($key, Category $category): void
    {
        if (is_string($key) || is_int($key)) {
            $this->categories[$key] = $category;
        }
    }

    /**
     * @param array $categories
     *
     */
    public function addCategories(array $categories): void
    {
        foreach($categories as $category) {
            if ($category instanceof Category) {
                $this->addCategory($category);
            }
        }
    }

    /**
     * @param $key
     *
     * @return Category|null
     */
    public function findByKey($key): ?Category
    {
        if (is_string($key) || is_int($key)) {
            return $this->categories[$key] ?? null;
        }

        return null;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->categories as $category) {
            $result[] = $category->toArray();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->categories);
    }
}