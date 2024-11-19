<?php

declare(strict_types=1);

namespace App\Base\Common\Collection;

final class DataCollection implements Arrayable, Countable
{
    /**
     * @var array
     */
    private array $items = [];

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param $key
     * @param $value
     *
     */
    public function set($key, $value): void
    {
        if (is_string($key) || is_int($key)) {
            $this->items[$key] = $value;
        }
    }

    /**
     * @param $value
     *
     */
    public function push($value): void
    {
        $this->items[] = $value;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function findByKey($key): mixed
    {
        if (is_string($key) || is_int($key)) {
            return $this->items[$key] ?? null;
        }

        return null;
    }

    /**
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->items as $key => $item) {
            if ($item instanceof Arrayable) {
                $item = $item->toArray();
            }

            $result[$key] = $item;
        }

        return $result;
    }

    /**
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }
}