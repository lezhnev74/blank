<?php

return [
    'name' => 'Migrations',
    'migrations_namespace' => 'Blank\Infrastructure\Database\Migrations',
    'table_name' => 'doctrine_migration_versions',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => implode(DIRECTORY_SEPARATOR, [
        __DIR__,
        'src',
        'Infrastructure',
        'Database',
        'Migrations',
    ]),
    'all_or_nothing' => true,
    'check_database_platform' => true,
];