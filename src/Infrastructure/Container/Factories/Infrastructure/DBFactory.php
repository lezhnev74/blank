<?php
declare(strict_types=1);


namespace Blank\Infrastructure\Container\Factories\Infrastructure;


use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;

class DBFactory
{
    public function __invoke(): Connection
    {
        $dbConfig = config('db');

        if ($dbConfig['driver'] !== 'sqlite') {
            throw new \RuntimeException('Unsupported db driver');
        }

        $connectionParams = [
            'url' => sprintf('sqlite:///%s', $dbConfig['file']),
        ];
        return DriverManager::getConnection($connectionParams);
    }
}
