<?php

declare(strict_types=1);

namespace App\UI\Api\Status;

use App\Base\Pet\Repository\StatusRepository;
use Nette;

final class StatusPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var StatusRepository
     */
    private StatusRepository $statusRepository;

    /**
     * @param StatusRepository $statusRepository
     */
    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }
    public function actionIndex()
    {
        $result = $this->statusRepository->find();

        $this->sendJson($result->toArray());
    }
}
