<?php

declare(strict_types=1);

namespace Blank\Infrastructure\Container\Factories\Infrastructure;

use PHLAK\Config\Config;

class ConfigFactory
{
    public function __invoke(): Config
    {
        $configData = require base_path(implode(DIRECTORY_SEPARATOR, ['src', 'Infrastructure', 'config.php']));
        return new Config($configData);
    }
}
