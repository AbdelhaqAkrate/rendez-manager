<?php

namespace App\Models\Creaneau;

use App\Models\AbstructModel;
use Carbon\Carbon;

class Creaneau extends AbstructModel
{
    public const TABLE = 'creneau';

    protected $table = self::TABLE;

    public const START_TIME_COLUMN ='start_time';
    public const END_TIME_COLUMN = 'end_time';
    public const DAY_COLUMN = 'day';

    protected $guarded = [];

    public function getStartTime(): string
    {
        return $this->getAttribute(self::START_TIME_COLUMN);
    }

    public function getEndTime(): string
    {
        return $this->getAttribute(self::END_TIME_COLUMN);
    }

    public function getDay(): string
    {
        return $this->getAttribute(self::DAY_COLUMN);
    }
}