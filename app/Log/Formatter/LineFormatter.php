<?php

namespace App\Log\Formatter;

use DateTimeZone;
use Monolog\Formatter\LineFormatter as BaseLineFormatter;
use Monolog\LogRecord;

class LineFormatter extends BaseLineFormatter
{
    public function format(LogRecord $record): string
    {
        $datetime = $record['datetime']->setTimezone(new DateTimeZone("UTC"))->format('Y-m-d H:i:s');
        $context = implode(",", $record['context']);
        $extra = implode(",", $record['extra']);

        return "$datetime,{$record['level']},{$record['message']},{$record['channel']},{$record['level_name']},$context,$extra" . PHP_EOL;
    }
}
