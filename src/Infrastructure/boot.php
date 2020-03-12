<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require_once 'global.php';


// A safety net, all catchable errors convert to exceptions
set_error_handler(function ($code, $message) {
    throw new ErrorException($message, $code);
});
set_exception_handler(function (Throwable $exception) {
    $monolog = container()->get(\Psr\Container\ContainerInterface::class);
    $monolog->error((string)$exception);
});
