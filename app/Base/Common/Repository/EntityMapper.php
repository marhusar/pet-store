<?php

namespace App\Base\Common\Repository;

use App\Base\Common\Collection\Arrayable;
use App\Base\Common\Collection\Countable;
use App\Base\Common\Collection\DataCollection;
use App\Base\Common\Collection\NumberCollection;
use App\Base\Common\Inflector;

class EntityMapper
{
    /**
     * @var DataCollection
     */
    private DataCollection $repositories;

    /**
     * @var Inflector
     */
    private Inflector $inflector;

    /**
     * @param DataCollection $repositories
     * @param Inflector      $inflector
     */
    public function __construct(DataCollection $repositories, Inflector $inflector)
    {
        $this->repositories = $repositories;
        $this->inflector    = $inflector;
    }

    /**
     * @param DataCollection $entity
     *
     * @return DataCollection
     */
    public function loadReferences(DataCollection $entity): DataCollection
    {
        $entityWithReferences = new DataCollection();

        foreach ($entity->all() as $propertyName => $propertyValue) {
            if (is_array($propertyValue) && count($propertyValue) === (new NumberCollection($propertyValue))->count()) {
                $entityName = rtrim($propertyName, 'Ids');
                $repository = $this->repositories->findByKey($entityName);

                if ($repository && method_exists($repository, 'findByIds')) {
                    $entities = $repository->findByIds(new NumberCollection($propertyValue));

                    if ($entities instanceof Arrayable && $entities instanceof Countable && $entities->count() === count($propertyValue)) {
                        $entityWithReferences->set($this->inflector->pluralize($entityName), $entities->toArray());
                        continue;
                    }
                }
            }

            if (is_int($propertyValue)) {
                $entityName = rtrim($propertyName, 'Id');
                $repository = $this->repositories->findByKey($entityName);

                if ($repository && method_exists($repository, 'findById')) {
                    $entity = $repository->findById($propertyValue);

                    if ($entity instanceof Arrayable) {
                        $entity = $entity->toArray();
                    }

                    $entityWithReferences->set($entityName, $entity);
                    continue;
                }
            }

            $entityWithReferences->set($propertyName, $propertyValue);
        }

        return $entityWithReferences;
    }
}