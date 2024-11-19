<?php

declare(strict_types=1);

namespace App\Base\Common\Collection;

final class NumberCollection implements Arrayable, Countable
{
    /**
     * @var int[]
     */
    private array $numbers = [];

    /**
     * @param array $numbers
     */
    public function __construct(array $numbers = [])
    {
        $this->addNumbers($numbers);
    }

    /**
     * @param int $number
     *
     */
    public function addNumber(int $number): void
    {
        $this->numbers[] = $number;
    }

    /**
     * @param array $numbers
     *
     */
    public function addNumbers(array $numbers): void
    {
        foreach($numbers as $number) {
            if (is_int($number)) {
                $this->addNumber($number);
            }
        }
    }

    /**
     *
     * @return int[]
     */
    public function toArray(): array
    {
        return $this->numbers;
    }

    /**
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->numbers);
    }
}