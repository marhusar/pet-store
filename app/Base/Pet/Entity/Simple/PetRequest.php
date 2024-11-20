<?php

declare(strict_types=1);

namespace App\Base\Pet\Entity\Simple;

use App\Base\Common\Collection\NumberCollection;
use App\Base\Common\Collection\TextCollection;

final class PetRequest
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $categoryId;

    /**
     * @var TextCollection|null
     */
    private ?TextCollection $photoUrls = null;

    /**
     * @var NumberCollection|null
     */
    private ?NumberCollection $tagIds = null;

    /**
     * @var int
     */
    private int $statusId;

    /**
     * @var int
     */
    private int $age;

    /**
     * @param string                $name
     * @param int                   $categoryId
     * @param TextCollection|null   $photoUrls
     * @param NumberCollection|null $tagIds
     * @param int                   $statusId
     * @param int                   $age
     */
    public function __construct(string $name, int $categoryId, ?TextCollection $photoUrls, ?NumberCollection $tagIds, int $statusId, int $age)
    {
        $this->name       = $name;
        $this->categoryId = $categoryId;
        $this->photoUrls  = $photoUrls;
        $this->tagIds     = $tagIds;
        $this->statusId   = $statusId;
        $this->age        = $age;
    }

    /**
	 * @return string
	 */
	public function getName(): string 
    {
		return $this->name;
	}

    /**
     *
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     *
     * @return TextCollection
     */
    public function getPhotoUrls(): TextCollection
    {
        return $this->photoUrls ? $this->photoUrls : new TextCollection();
    }

    /**
     *
     * @return NumberCollection
     */
    public function getTagIds(): NumberCollection
    {
        return $this->tagIds ? $this->tagIds : new NumberCollection();
    }


    /**
     *
     * @return int
     */
    public function getStatusId(): int
    {
		return $this->statusId;
	}

    /**
     *
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        foreach ($properties as $key => &$property) {
            if (is_object($property) && method_exists($property, 'toArray', )) {
                $property = $property->toArray();
            }
        }

        return $properties;
    }
}