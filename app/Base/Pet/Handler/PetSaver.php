<?php

namespace App\Base\Pet\Handler;

use App\Base\Common\Collection\DataCollection;
use App\Base\Pet\Normalizer\PetNormalizer;
use App\Base\Pet\Repository\PetRepository;

class PetSaver
{
    /**
     * @var PetNormalizer
     */
    private PetNormalizer $normalizer;

    /**
     * @var PetRepository
     */
    private PetRepository $petRepository;

    /**
     * @param PetNormalizer $normalizer
     * @param PetRepository $petRepository
     */
    public function __construct(PetNormalizer $normalizer, PetRepository $petRepository)
    {
        $this->normalizer    = $normalizer;
        $this->petRepository = $petRepository;
    }

    /**
     * @param int            $id
     * @param DataCollection $data
     *
     * @return DataCollection
     */
    public function update(int $id, DataCollection $data): DataCollection
    {
        $pet = $this->normalizer->denormalize($data);

        return $this->petRepository->updatePet($id, $pet);
    }

    /**
     * @param DataCollection $data
     *
     * @return DataCollection
     */
    public function store(DataCollection $data): DataCollection
    {
        $pet = $this->normalizer->denormalize($data);

        return $this->petRepository->storePet($pet);
    }

    /**
     *
     * @return DataCollection
     */
    public function getSchema(): DataCollection
    {
        return $this->normalizer->getSchema();
    }
}