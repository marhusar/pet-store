<?php

namespace App\Base\Pet\Factory;

use App\Base\Pet\Collection\CategoryCollection;
use App\Base\Pet\Entity\Category;

class CategoryFactory
{
    /**
     * @param array $data
     *
     * @return CategoryCollection
     */
    public  function createCategories(array $data): CategoryCollection
    {
        $categories = new CategoryCollection();

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            $category = $this->createCategory($value);

            if ($category) {
                $categories->setCategory($category->getId(), $category);
            }
        }

        return $categories;
    }

    /**
     * @param array $data
     *
     * @return Category|null
     */
    public function createCategory(array $data): ?Category
    {
        $id = $data['id'] ?? null;
        $name = $data['name'] ?? null;

        if ($id && $name) {
            return new Category($id, $name);
        }

        return null;
    }
}