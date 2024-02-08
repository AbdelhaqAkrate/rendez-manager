<?php

namespace App\Services;

use App\Log\AppLogger;

abstract class Service
{
    protected AppLogger $appLogger;

    public function __construct()
    {
        $this->appLogger = app(AppLogger::class);
    }
}
