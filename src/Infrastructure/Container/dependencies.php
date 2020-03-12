<?php

declare(strict_types=1);

namespace Blank\Infrastructure;

use Doctrine\DBAL\Driver\Connection;
use PHLAK\Config\Config;
use Blank\Infrastructure\Container\Factories\Infrastructure\ConfigFactory;
use Blank\Infrastructure\Container\Factories\Infrastructure\DBFactory;
use Blank\Infrastructure\Container\Factories\Infrastructure\LoggerFactory;
use Blank\Infrastructure\Container\Factories\Infrastructure\SlimFactory;
use Psr\Log\LoggerInterface;
use Slim\App;
use function DI\factory;

return [
    // Application
    // Domain
    // Infrastructure
    LoggerInterface::class => factory(LoggerFactory::class),
    Config::class => factory(ConfigFactory::class),
    Connection::class => factory(DBFactory::class),
    App::class => factory(SlimFactory::class),
];
