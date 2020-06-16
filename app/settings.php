<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions(
        [
            'settings' => [
                'environment' => $_ENV['ENV'] ?? 'production',
                'clientEndpoint' => $_ENV['CLIENT_ENDPOINT'],
                'displayErrorDetails' => $_ENV['ENV'] == 'dev',
                'logSql' => $_ENV['LOG_SQL'] == 'true',
                'logger' => [
                    'name' => 'visitor-management-service',
                    'path' => $_ENV['LOG_TARGET'] ?? 'php://stdout',
                    'level' => $_ENV['LOG_LEVEL'] ?? Logger::DEBUG,
                ],
                'doctrine' => [
                    'dev_mode' => $_ENV['ENV'] ?? 'dev',
                    'cache_dir' => APP_ROOT . 'var/doctrine',
                    'metadata_dirs' => [APP_ROOT . 'src/Domain'],
                    'connection' => [
                        'driver' => 'pdo_pgsql',
                        'charset' => 'utf-8',
                        'host' => $_ENV['POSTGRES_HOST'] ?? 'localhost',
                        'port' => $_ENV['POSTGRES_PORT'] ?? 3306,
                        'dbname' => $_ENV['POSTGRES_DB'] ?? 'navigate',
                        'user' => $_ENV['POSTGRES_USER'] ?? 'user',
                        'password' => $_ENV['POSTGRES_PASSWORD'] ?? 'password'
                    ]
                ],
                'database' => [
                    'host' => $_ENV['POSTGRES_HOST'] ?? 'localhost',
                    'port' => $_ENV['POSTGRES_PORT'] ?? 3306,
                    'dbname' => $_ENV['POSTGRES_DB'] ?? 'navigate',
                    'user' => $_ENV['POSTGRES_USER'] ?? 'user',
                    'password' => $_ENV['POSTGRES_PASSWORD'] ?? 'password'
                ],
                'redis' => [
                    'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
                    'port' => $_ENV['REDIS_PORT'] ?? '6379'
                ]
            ],
        ]
    );
};
