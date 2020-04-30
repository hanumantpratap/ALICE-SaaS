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

//doctrine stuff
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

use App\Classes\DatabaseConnection;
use App\Classes\RedisConnector;
use App\Classes\TokenProcessor;

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
            $db = new DatabaseConnection($config, $logger);
            return $db;
        },
        DatabaseConnection::class => DI\get('CoreDB'),

        'DistrictDB' => function (ContainerInterface $c, LoggerInterface $logger) {
            $config = $c->get('settings')['database'];
            $secureId = $c->get('secureID');
            $db = new DistrictDatabaseConnection($secureId, $config, $logger);
            return $db;
        },
        DistrictDatabaseConnection::class => DI\get('CoreDB'),

        RedisConnector::class => function (ContainerInterface $c) {
            $config = $c->get('settings')['redis'];
            $redis = new RedisConnector(null, $config, $logger);
            return $redis;
        },

        TokenProcessor::class => function (ContainerInterface $c, RedisConnector $redis) {
            return new TokenProcessor($redis);
        },

        'EntityManager' => function (ContainerInterface $c): EntityManager {
            $doctrineSettings = $c->get('settings')['doctrine'];
            $secureId = $c->get('secureID');
            $doctrineSettings['connection']['dbname'] = $doctrineSettings['connection']['dbname'] . '_' . $secureId;
	    	
            $config = Setup::createAnnotationMetadataConfiguration(
                $doctrineSettings['metadata_dirs'],
                $doctrineSettings['dev_mode']
            );

            $config->setMetadataDriverImpl(
                new AnnotationDriver(
                    new AnnotationReader,
                    $doctrineSettings['metadata_dirs']
                )
            );

            $config->setMetadataCacheImpl(
                new FilesystemCache($doctrineSettings['cache_dir'])
            );

            return EntityManager::create($doctrineSettings['connection'], $config);
        },
        EntityManagerInterface::class => DI\get('EntityManager')
    ]);
};
