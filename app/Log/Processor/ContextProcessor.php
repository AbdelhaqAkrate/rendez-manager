<?php

namespace App\Log\Processor;

use Monolog\LogRecord;

class ContextProcessor
{
    public function __invoke(LogRecord $record)
    {
        if (app()->runningInConsole()) {
            return $record;
        }

        $record['extra'] = [
            'http.request.host'     => request()->server('HTTP_HOST'),
            'http.request.method'   => strtolower(request()->server('REQUEST_METHOD')),
            'http.request.protocol' => request()->secure() ? 'https' : 'http',
            'source.address'        => request()->getClientIp(),
            'source.ip'             => request()->getClientIp(),
            'url.original'          => request()->server('REQUEST_URI'),
            'http.request.uri'      => request()->path()
        ];

        return $record;
    }
}
