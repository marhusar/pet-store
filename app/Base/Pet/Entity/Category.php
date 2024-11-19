<?php

namespace App\Base\Pet\Entity;

use App\Base\Common\Collection\Arrayable;

class Category implements Arrayable
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param int    $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}