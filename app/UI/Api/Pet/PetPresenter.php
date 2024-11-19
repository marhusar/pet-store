<?php

declare(strict_types=1);

namespace App\UI\Api\Pet;

use App\Base\Common\Collection\DataCollection;
use App\Base\Pet\Handler\PetProvider;
use App\Base\Pet\Handler\PetSaver;
use App\Base\Pet\Repository\PetRepository;
use Exception;
use Nette;
use Nette\Application\AbortException;

final class PetPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var PetRepository
     */
    private PetRepository $petRepository;

    /**
     * @var PetProvider
     */
    private PetProvider $petProvider;

    /**
     * @var PetSaver
     */
    private PetSaver $petSaver;

    /**
     * @param PetRepository $petRepository
     * @param PetProvider   $petProvider
     * @param PetSaver      $petSaver
     */
    public function __construct(PetRepository $petRepository, PetProvider $petProvider, PetSaver $petSaver)
    {
        $this->petRepository = $petRepository;
        $this->petProvider   = $petProvider;
        $this->petSaver      = $petSaver;
    }

    /**
     * @param int $id
     *
     */
    public function actionHandle(int $id)
    {
        try {
            switch ($this->getRequest()->getMethod()) {
                case 'GET':
                    $result = $this->petRepository->getById($id)->toArray();
                    break;
                case 'POST':
                    break;
                case 'DELETE':
                    $this->petRepository->deleteById($id);
                    $result = ['Pet deleted'];
                    break;
                default:
                    throw new AbortException('Method not allowed', 405);
            }
        } catch (Exception $exception) {
            $result = ['error' => $exception->getMessage()];
            $this->getHttpResponse()->setCode($exception->getCode());
        }

        $this->sendJson($result);
    }

    public function actionIndex()
    {
        $offset = (int) $this->getParameter('offset') ?? 0;
        $limit = (int) $this->getParameter('limit') ?? null;

        $result = $this->petRepository->find($offset, $limit);

        $this->sendJson($result->toArray());
    }


    /**
     *
     */
    public function actionIndexByTags()
    {
        if (!$this->isMethod('GET')) {
             throw new AbortException('Method not allowed', 405);
        }

        $tags   = $this->getParameter('tags') ?? [];
        $result = $this->petProvider->findByTags(new DataCollection($tags))->toArray();

        $this->sendJson($result);
    }

    /**
     *
     */
    public function actionIndexByStatus()
    {
        if (!$this->isMethod('GET')) {
            throw new AbortException('Method not allowed', 405);
        }

        try {
            $status = $this->getParameter('status') ?? 'available';
            $result = $this->petProvider->findByStatus($status)->toArray();
        } catch (Exception $exception) {
            $this->getHttpResponse()->setCode($exception->getCode());
            $result = ['error' => $exception->getMessage()];
        }

        $this->sendJson($result);
    }

    /**
     *
     */
    public function actionStore()
    {
        $body = $this->getRequestBody();
        $id = $body->findByKey('id');

        try {
            switch ($this->getRequest()->getMethod()) {
                case 'PUT':
                    if (!$id) {
                        throw new Exception('ID is missing', 400);
                    }
                    $result = $this->petSaver->update($id, $body)->toArray();
                    break;
                case 'POST':
                    $result = $this->petSaver->store($body)->toArray();
                    break;
                default:
                    throw new AbortException('Method not allowed', 405);
            }
        } catch (Exception $exception) {
            $result = ['error' => $exception->getMessage()];
            $this->getHttpResponse()->setCode( $exception->getCode());
        }

        $this->sendJson($result);
    }

    public function actionSchema()
    {
        $this->sendJson($this->petSaver->getSchema()->toArray());
    }

    /**
     *
     * @return DataCollection
     */
    private function getRequestBody(): DataCollection
    {
        return new DataCollection(json_decode($this->getHttpRequest()->getRawBody(), true) ?? []);
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    private function isMethod(string $method): bool
    {
        return $this->getHttpRequest()->getMethod() === $method;
    }
}
