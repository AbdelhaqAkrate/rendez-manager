<?php

namespace App\Log;

use Illuminate\Log\Logger;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;

class AppLogger extends Logger
{
    public function __construct(LoggerInterface $logger, $dispatcher = null)
    {
        parent::__construct(app(LogManager::class)->channel('app_logger'), $dispatcher);
    }

    protected function writeLog($level, $message, $context): void
    {
        $preparedContext = [];

        if (is_array($context)) {
            foreach ($context as $key => $item) {
                if (is_array($item)) {
                    $item = json_encode($item);
                }
                $preparedContext[$key] = $item;
            }
        }

        parent::writeLog($level, $message, $preparedContext);
    }
}
