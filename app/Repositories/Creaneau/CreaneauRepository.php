<?php

namespace App\Repositories\Creaneau;

use App\Models\Creaneau\Creaneau;
use Illuminate\Support\Arr;
use App\Repositories\Repository;

class CreaneauRepository extends Repository
{
    public function create(array $attributes): Creaneau
    {
        return Creaneau::create([
            Creaneau::START_TIME_COLUMN => Arr::get($attributes, Creaneau::START_TIME_COLUMN),
            Creaneau::END_TIME_COLUMN => Arr::get($attributes, Creaneau::END_TIME_COLUMN),
            Creaneau::DAY_COLUMN => Arr::get($attributes, Creaneau::DAY_COLUMN),
        ]);
    }
}
