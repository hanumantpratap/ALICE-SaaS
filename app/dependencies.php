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
use App\Classes\DistrictDatabaseConnection;
use App\Classes\Mailer;
use App\Classes\RedisConnector;
use App\Classes\TokenProcessor;
use App\Classes\SqlLogger;

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

        DatabaseConnection::class => function (ContainerInterface $c, LoggerInterface $logger) {
            $logger->info('create core db');
            $config = $c->get('settings')['database'];
	    
            $db = new DatabaseConnection($config, $logger);
            return $db;
        },

        DistrictDatabaseConnection::class => function (ContainerInterface $c, LoggerInterface $logger) {
            $logger->info('create district db');
            $config = $c->get('settings')['database'];
            
            if (!$c->has('secureID')) {
                throw new \App\Exceptions\InternalServerErrorException('No Secure ID set');
            }
            
            $secureId = $c->get('secureID');
            
            $db = new DistrictDatabaseConnection($secureId, $config, $logger);
            return $db;
        },

        RedisConnector::class => function (ContainerInterface $c) {
            $config = $c->get('settings')['redis'];
            $redis = new RedisConnector($config);
            return $redis;
        },

        TokenProcessor::class => function (RedisConnector $redis) {
            return new TokenProcessor($redis);
        },

        Mailer::class => function (ContainerInterface $c, LoggerInterface $logger) {
            $settings = $c->get('settings');
            $config = [
                'connection' => [
                    'version' => '2010-12-01',
                    'region' => 'us-east-1'
                ],
                'devMode' => false
            ];
            
            if ($settings['environment'] == 'dev') {
                $config['connection']['profile'] = 'ses_admin';
                $config['devMode'] = true;
            }

            return new Mailer($config, $logger);
        },

        EntityManagerInterface::class => function (ContainerInterface $c, LoggerInterface $logger): EntityManager {
            $settings = $c->get('settings');
            $doctrineSettings = $settings['doctrine'];
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

            if ($settings['logSql']) {
                $sqlLogger = new SqlLogger($logger);
                $config->setSQLLogger($sqlLogger);
            }

            $config->setAutoGenerateProxyClasses(true);

            if ($settings['environment'] != 'dev') {
                $config->setMetadataCacheImpl(
                    new FilesystemCache($doctrineSettings['cache_dir'])
                );
            }

            return EntityManager::create($doctrineSettings['connection'], $config);
        }
    ]);
};