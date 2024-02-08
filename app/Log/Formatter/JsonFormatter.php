<?php

namespace App\Log\Formatter;

use DateTimeZone;
use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;
use Monolog\LogRecord;

class JsonFormatter extends BaseJsonFormatter
{
    public function format(LogRecord $record): string
    {
        /** @var \DateTime $recordDate */
        $recordDate = $record['datetime']->setTimezone(new DateTimeZone('UTC'));
        $newRecord = [
            'time' => $recordDate->format('c'),
            'channel' => $record['channel'],
            'level_name' => $record['level_name'],
            'level' => $record['level'],
            'message' => $record['message'],
        ];

        if (!empty($record['context'])) {
            $newRecord = array_merge($newRecord, $record['context']);
        }

        if (!empty($record['extra'])) {
            $newRecord = array_merge($newRecord, $record['extra']);
        }

        return $this->toJson($newRecord).($this->appendNewline ? "\n" : '');
    }
}
