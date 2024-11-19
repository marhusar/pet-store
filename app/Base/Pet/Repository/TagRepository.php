<?php

namespace App\Base\Pet\Repository;

use App\Base\Common\Collection\NumberCollection;
use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Entity\Tag;

interface TagRepository
{
    /**
     * @param int $id
     *
     * @return Tag|null
     */
    public function findById(int $id): ?Tag;

    /**
     * @param NumberCollection $ids
     *
     * @return TagCollection
     */
    public function findByIds(NumberCollection $ids): TagCollection;

    /**
     * @param string $name
     *
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag;

    /**
     *
     * @return TagCollection
     */
    public function find(): TagCollection;
}