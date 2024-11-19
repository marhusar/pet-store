<?php

declare(strict_types=1);

namespace App\UI\Api\Tag;

use App\Base\Pet\Repository\TagRepository;
use Nette;

final class TagPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function actionIndex()
    {
        $result = $this->tagRepository->find();

        $this->sendJson($result->toArray());
    }
}
