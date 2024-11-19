<?php

namespace App\Base\Pet\Normalizer;

use App\Base\Common\Collection\DataCollection;
use App\Base\Pet\Entity\Simple\PetRequest;

interface PetNormalizer
{

    /**
     * @param DataCollection $data
     *
     * @return PetRequest
     */
    public function denormalize(DataCollection $data): PetRequest;

    /**
     *
     * @return DataCollection
     */
    public function getSchema(): DataCollection;
}