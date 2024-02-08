<?php

namespace App\Log;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use App\Log\Processor\ContextProcessor;

/**
 * Class LogLineHandler
 * @package App\Core\Log
 */
class LogLineHandler
{
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor(new ContextProcessor());
            $handler->setFormatter(new LineFormatter());
        }
    }
}
