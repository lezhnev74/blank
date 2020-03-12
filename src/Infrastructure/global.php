<?php

declare(strict_types=1);

function base_path(string $sub_path = ''): string
{
    if ($sub_path) $sub_path = DIRECTORY_SEPARATOR . $sub_path;
    return realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..'])) . $sub_path;
}

/**
 * Read a value for .env file
 * If not present, return a default value
 */
function env($key, $default = null)
{
    static $dotenv;
    if (!$dotenv) {
        $dotenv = Dotenv\Dotenv::createImmutable(base_path());
        $dotenv->load();
    }

    $value = getenv($key);
    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
        default:
            return $value;
    }
}

function container($reset = false): \Psr\Container\ContainerInterface
{
    static $container;

    if ($container && !$reset) {
        return $container;
    }

    $builder = new \DI\ContainerBuilder();
    $builder->useAnnotations(false);
    $builder->useAutowiring(true);
    if (env('APP_ENV') === 'production') {
        $builder->enableCompilation(base_path(implode(DIRECTORY_SEPARATOR, ['storage', 'tmp'])));
    }
    $builder->addDefinitions(base_path(
        DIRECTORY_SEPARATOR . 'src' .
        DIRECTORY_SEPARATOR . 'Infrastructure' .
        DIRECTORY_SEPARATOR . 'Container' .
        DIRECTORY_SEPARATOR . 'dependencies.php'
    ));
    $container = $builder->build();

    return $container;
}

/**
 * Resolve a given dependency with manually given dependencies
 */
function make(string $dependency, array $manualDependencies = [])
{
    /** @var \DI\Container $container */
    $container = container();
    return $container->make($dependency, $manualDependencies);
}

function logMessage(string $message, array $context = []): void
{
    container()->get(\Psr\Log\LoggerInterface::class)->info($message, $context);
}

function config(string $key)
{
    $config = container()->get(PHLAK\Config\Config::class);
    return $config->get($key);
}

/** Debug method to quickly execute SQL queries (used in IDE debugger to inspect the current state of the app) */
function debugQuery(string $query): array
{
    return container()->get(\Doctrine\DBAL\Driver\Connection::class)->query($query)->fetchAll();
}
