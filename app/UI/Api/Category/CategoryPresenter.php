<?php

declare(strict_types=1);

namespace App\UI\Api\Category;

use App\Base\Pet\Repository\CategoryRepository;
use Nette;

final class CategoryPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function actionIndex()
    {
        $result = $this->categoryRepository->find();

        $this->sendJson($result->toArray());
    }
}
