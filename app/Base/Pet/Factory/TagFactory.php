<?php

namespace App\Base\Pet\Factory;

use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Entity\Tag;

class TagFactory
{
    /**
     * @param array $data
     *
     * @return TagCollection
     */
    public  function createTags(array $data): TagCollection
    {
        $tags = new TagCollection();

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            $Tag = $this->createTag($value);

            if ($Tag) {
                $tags->setTag($Tag->getId(), $Tag);
            }
        }

        return $tags;
    }

    /**
     * @param array $data
     *
     * @return Tag|null
     */
    public function createTag(array $data): ?Tag
    {
        $id = $data['id'] ?? null;
        $name = $data['name'] ?? null;

        if ($id && $name) {
            return new Tag($id, $name);
        }

        return null;
    }
}