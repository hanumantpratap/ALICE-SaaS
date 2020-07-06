<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions(
        [
            'settings' => [
                'environment' => getenv('ENV') ?? 'production',
                'clientUrl' => getenv('CLIENT_URL') ?? 'http://localhost:3000',
                'authUrl' => getenv('AUTH_URL') ?? 'https://test-auth.navigatep.com',
                'displayErrorDetails' => getenv('ENV') == 'dev',
                'logSql' => getenv('LOG_SQL') != 'true',
                'logger' => [
                    'name' => 'visitor-management-service',
                    'path' => getenv('LOG_TARGET') ?? 'php://stdout',
                    'level' => getenv('LOG_LEVEL') ?? Logger::DEBUG,
                ],
                'doctrine' => [
                    'dev_mode' => getenv('ENV') ?? 'dev',
                    'cache_dir' => APP_ROOT . 'var/doctrine',
                    'metadata_dirs' => [APP_ROOT . 'src/Domain'],
                    'connection' => [
                        'driver' => 'pdo_pgsql',
                        'charset' => 'utf-8',
                        'host' => getenv('POSTGRES_HOST') ?? 'localhost',
                        'port' => getenv('POSTGRES_PORT') ?? 3306,
                        'dbname' => getenv('POSTGRES_DB') ?? 'navigate',
                        'user' => getenv('POSTGRES_USER') ?? 'user',
                        'password' => getenv('POSTGRES_PASSWORD') ?? 'password'
                    ]
                ],
                'database' => [
                    'host' => getenv('POSTGRES_HOST') ?? 'localhost',
                    'port' => getenv('POSTGRES_PORT') ?? 3306,
                    'dbname' => getenv('POSTGRES_DB') ?? 'navigate',
                    'user' => getenv('POSTGRES_USER') ?? 'user',
                    'password' => getenv('POSTGRES_PASSWORD') ?? 'password'
                ],
                'redis' => [
                    'host' => getenv('REDIS_HOST') ?? 'localhost',
                    'port' => getenv('REDIS_PORT') ?? '6379'
                ]
            ],
        ]
    );
};
