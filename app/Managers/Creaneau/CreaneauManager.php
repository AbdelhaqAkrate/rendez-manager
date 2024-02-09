<?php

namespace App\Managers\Creaneau;

use App\Managers\Manager;
use App\Models\Creaneau\Creaneau;
use App\Repositories\Creaneau\CreaneauRepository;

class CreaneauManager extends Manager
{
    public function __construct(
        private CreaneauRepository $creaneauRepository
    ) {
    }

    public function create(array $attributes): ?Creaneau
    {
        return $this->creaneauRepository->create($attributes);
    }
}
