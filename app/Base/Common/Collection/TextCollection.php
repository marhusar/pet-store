<?php

declare(strict_types=1);

namespace App\Base\Common\Collection;

class TextCollection implements Arrayable, Countable
{
    /**
     * @var string[]
     */
    private array $texts = [];

    /**
     * @param array $texts
     */
    public function __construct(array $texts = [])
    {
        $this->addTexts($texts);
    }

    /**
     * @param string $text
     *
     */
    public function addText(string $text)
    {
        $this->texts[] = $text;
    }

    /**
     * @param array $texts
     *
     */
    public function addTexts(array $texts)
    {
        foreach($texts as $text) {
            if (is_string($text)) {
                $this->addText($text);
            }
        }
    }

    /**
     *
     * @return string[]
     */
    public function toArray(): array
    {
        return $this->texts;
    }

    /**
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->texts);
    }
}