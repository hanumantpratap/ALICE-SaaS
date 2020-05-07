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

use Doctrine\DBAL\Types\Type;

Type::overrideType('datetime', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('datetimetz', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('time', 'Doctrine\DBAL\Types\VarDateTimeType');

Type::overrideType('datetime_immutable', 'Doctrine\DBAL\Types\VarDateTimeImmutableType');
Type::overrideType('datetimetz_immutable', 'Doctrine\DBAL\Types\VarDateTimeImmutableType');
Type::overrideType('time_immutable', 'Doctrine\DBAL\Types\VarDateTimeImmutableType');

use App\Classes\DatabaseConnection;
use App\Classes\DistrictDatabaseConnection;
use App\Classes\RedisConnector;
use App\Classes\TokenProcessor;
use App\Classes\VisitsService;
use App\Domain\Person\PersonRepository;
use App\Infrastructure\Persistence\Person\SqlPersonRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PersonRepository::class => function(LoggerInterface $logger, EntityManagerInterface $em) {
            return new SqlPersonRepository($logger, $em);
        },
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
            $logger->info('create core db');
            $config = $c->get('settings')['database'];
	    
            $db = new DatabaseConnection($config, $logger);
            return $db;
        },
        DatabaseConnection::class => DI\get('CoreDB'),

        'DistrictDB' => function (ContainerInterface $c, LoggerInterface $logger) {
            $logger->info('create district db');
            $config = $c->get('settings')['database'];
            
            if (!$c->has('secureID')) {
                throw new \App\Exceptions\InternalServerErrorException('No Secure ID set');
            }
            
            $secureId = $c->get('secureID');
            
            $db = new DistrictDatabaseConnection($secureId, $config, $logger);
            return $db;
        },
        DistrictDatabaseConnection::class => DI\get('DistrictDB'),

        RedisConnector::class => function (ContainerInterface $c) {
            $config = $c->get('settings')['redis'];
            $redis = new RedisConnector(null, $config, $logger);
            return $redis;
        },

        TokenProcessor::class => function (ContainerInterface $c) {
            //return new TokenProcessor($redis);
            return new TokenProcessor();
        },

        'EntityManager' => function (ContainerInterface $c, LoggerInterface $logger): EntityManager {
            $doctrineSettings = $c->get('settings')['doctrine'];
            $logger->info('entity manager');
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
        EntityManagerInterface::class => DI\get('EntityManager'),

        VisitsService::class => DI\create()->constructor(DI\get(DistrictDatabaseConnection::class), DI\get(LoggerInterface::class)),
    ]);
};