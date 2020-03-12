<?php

declare(strict_types=1);

namespace Blank\Infrastructure\Container\Factories\Infrastructure;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(): LoggerInterface
    {
        $log = new Logger('app');
        $path = base_path('storage' . DIRECTORY_SEPARATOR . 'log.log');
        $handler = new StreamHandler($path);
        $formatter = new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            true  // ignoreEmptyContextAndExtra option, default false
        );
        $handler->setFormatter($formatter);
        $log->pushHandler($handler);

        return $log;
    }
}
