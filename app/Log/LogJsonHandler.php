<?php

namespace App\Log;

use App\Log\Formatter\JsonFormatter;
use App\Log\Processor\ContextProcessor;
use Illuminate\Log\Logger;

/**
 * Class LogJsonHandler
 * @package App\Core\Log
 */
class LogJsonHandler
{
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor(new ContextProcessor());
            $handler->setFormatter(new JsonFormatter());
        }
    }
}
