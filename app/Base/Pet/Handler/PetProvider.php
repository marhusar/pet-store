<?php

declare(strict_types=1);

namespace App\Base\Pet\Handler;

use App\Base\Common\Collection\DataCollection;
use App\Base\Pet\Collection\TagCollection;
use App\Base\Pet\Repository\PetRepository;
use App\Base\Pet\Repository\StatusRepository;
use App\Base\Pet\Repository\TagRepository;
final class PetProvider
{
    /**
     * @var PetRepository
     */
    private PetRepository $petRepository;

    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * @var StatusRepository
     */
    private StatusRepository $statusRepository;

    /**
     * @param PetRepository    $petRepository
     * @param TagRepository    $tagRepository
     * @param StatusRepository $statusRepository
     */
    public function __construct(PetRepository $petRepository, TagRepository $tagRepository, StatusRepository $statusRepository)
    {
        $this->petRepository    = $petRepository;
        $this->tagRepository    = $tagRepository;
        $this->statusRepository = $statusRepository;
    }

    /**
     * @param DataCollection $data
     *
     * @return DataCollection
     */
    public function findByTags(DataCollection $data): DataCollection
    {
        $tags = new TagCollection();

        foreach ($data->all() as $tagName) {
            $tag = $this->tagRepository->findByName($tagName);

            if ($tag) {
                $tags->addTag($tag);
            }
        }

        return $this->petRepository->findByTags($tags);
    }

    /**
     * @param string $status
     *
     * @return DataCollection
     */
    public function findByStatus(string $status): DataCollection
    {
        $statusId = $this->statusRepository->getByName($status);

        return $this->petRepository->findByStatus($statusId);
    }
}