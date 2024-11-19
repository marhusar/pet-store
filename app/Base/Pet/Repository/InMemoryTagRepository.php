<?php

namespace App\Base\Pet\Repository;

use App\Base\Common\Collection\NumberCollection;
use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Entity\Tag;

class InMemoryTagRepository implements TagRepository
{
    /**
     * @var TagCollection
     */
    private TagCollection $tags;

    /**
     * @param TagCollection $tags
     */
    public function __construct(TagCollection $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param int $id
     *
     * @return Tag|null
     */
    public function findById(int $id): ?Tag
    {
        return $this->tags->findByKey($id);
    }

    /**
     * @param NumberCollection $ids
     *
     * @return TagCollection
     */
    public function findByIds(NumberCollection $ids): TagCollection
    {
        return $this->tags->findByKeys($ids->toArray());
    }

    /**
     * @param string $name
     *
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag
    {
        return $this->tags->findByName($name);
    }

    /**
     *
     * @return TagCollection
     */
    public function find(): TagCollection
    {
        return $this->tags;
    }
}