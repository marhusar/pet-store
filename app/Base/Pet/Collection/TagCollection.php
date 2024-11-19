<?php

declare(strict_types=1);

namespace App\Base\Pet\Collection;

use App\Base\Common\Collection\Arrayable;
use App\Base\Common\Collection\Countable;
use App\Base\Pet\Entity\Tag;

final class TagCollection implements Arrayable, Countable
{
    /**
     * @var Tag[]
     */
    private array $tags = [];

    /**
     * @param array $tags
     */
    public function __construct(array $tags = [])
    {
        $this->addTags($tags);
    }

    /**
     * @param Tag $tag
     *
     */
    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * @param array $tags
     *
     */
    public function addTags(array $tags): void
    {
        foreach($tags as $tag) {
            if ($tag instanceof Tag) {
                $this->addTag($tag);
            }
        }
    }

    /**
     * @param     $key
     * @param Tag $tag
     *
     */
    public function setTag($key, Tag $tag): void
    {
        if (is_string($key) || is_int($key)) {
            $this->tags[$key] = $tag;
        }
    }

    /**
     * @param $key
     *
     * @return Tag|null
     */
    public function findByKey($key): ?Tag
    {
        if (is_string($key) || is_int($key)) {
            return $this->tags[$key] ?? null;
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag
    {
        foreach ($this->tags as $tag) {
            if ($name === $tag->getName()) {
                return $tag;
            }
        }

        return null;
    }

    /**
     * @param array $keys
     *
     * @return TagCollection
     */
    public function findByKeys(array $keys): TagCollection
    {
        $tags = new TagCollection();

        foreach ($keys as $key) {
            $tag = $this->findByKey($key);

            if ($tag) {
                $tags->addTag($tag);
            }
        }

        return $tags;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->tags as $tag) {
            $result[] = $tag->toArray();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->tags);
    }

    /**
     *
     * @return Tag[]
     */
    public function all(): array
    {
        return $this->tags;
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