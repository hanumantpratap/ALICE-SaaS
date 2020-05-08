<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions(
        [
            'settings' => [
                'displayErrorDetails' => $_ENV['env'] == 'dev',
                'logger' => [
                    'name' => 'visitor-management-service',
                    'path' => $_ENV['LOG_TARGET'],
                    'level' => $_ENV['LOG_LEVEL'] ?? Logger::DEBUG,
                ],
                'doctrine' => [
                    'dev_mode' => $_ENV['ENV'] == 'dev',
                    'cache_dir' => APP_ROOT . '/var/doctrine',
                    'metadata_dirs' => [APP_ROOT . '/src/Entities'],
                    'connection' => [
                        'driver' => 'pdo_pgsql',
                        'charset' => 'utf-8',
                        'host' => $_ENV['POSTGRES_HOST'] ?? 'localhost',
                        'port' => $_ENV['POSTGRES_PORT'] ?? 3306,
                        'dbname' => $_ENV['POSTGRES_DB'],
                        'user' => $_ENV['POSTGRES_USER'],
                        'password' => $_ENV['POSTGRES_PASSWORD']
                    ]
                ],
                'database' => [
                    'host' => $_ENV['POSTGRES_HOST'] ?? 'localhost',
                    'port' => $_ENV['POSTGRES_PORT'] ?? 3306,
                    'dbname' => $_ENV['POSTGRES_DB'],
                    'user' => $_ENV['POSTGRES_USER'],
                    'password' => $_ENV['POSTGRES_PASSWORD']
                ],
                'redis' => [
                    'host' => $_ENV['REDIS_HOST'],
                    'port' => $_ENV['REDIS_PORT']
                ]
            ],
        ]
    );
};
