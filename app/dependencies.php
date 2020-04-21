<?php
declare(strict_types=1);

//TODO: http://php-di.org/doc/lazy-injection.html - interesting concept of lazy injecting other container services into an object
// http://php-di.org/doc/php-definitions.html#autowired-objects

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;


use App\Classes\DatabaseConnection;
return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'Logger' => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        LoggerInterface::class => DI\get('Logger'),

        'CoreDB' => function (ContainerInterface $c, LoggerInterface $logger) {
            $config = $c->get('settings')['database'];
            $logger->info('config', $config);
            $db = new DatabaseConnection(null, $config, $logger);
            return $db;
        },
        DatabaseConnection::class => DI\get('CoreDB'),
        'Foo' => function (ContainerInterface $c) {
            return 'Hello World';
        },
    ]);
};
