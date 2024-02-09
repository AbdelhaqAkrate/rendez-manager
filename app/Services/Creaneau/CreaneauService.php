<?php

namespace App\Services\Creaneau;

use App\Managers\Creaneau\CreaneauManager;
use App\Managers\Manager;
use App\Models\Creaneau\Creaneau;

class CreaneauService extends Manager
{
    public function __construct(
        private CreaneauManager $creaneauManager
    ) {
    }

    public function create(array $attributes): ?Creaneau
    {
        return $this->creaneauManager->create($attributes);
    }
}
