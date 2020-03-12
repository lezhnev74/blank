<?php
declare(strict_types=1);

// DOCTRINE CONFIG
// @see https://www.doctrine-project.org/projects/doctrine-migrations/en/2.2/reference/configuration.html#configuration

require_once implode(DIRECTORY_SEPARATOR, [__DIR__, 'src', 'Infrastructure', 'boot.php']);

// this connection must be automatically selected
$connection = container()->get(\Doctrine\DBAL\Driver\Connection::class);

return new \Symfony\Component\Console\Helper\HelperSet([
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($connection),
]);