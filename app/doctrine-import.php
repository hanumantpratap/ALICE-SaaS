<?php
/**
 * Script for importing existing tables as doctrine entities,
 */

include_once __DIR__ . '/../vendor/autoload.php';
define('APP_ROOT', realpath(__DIR__ . '/../') . '/');

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

echo "get container";

$container = include_once __DIR__ . '/doctrine-bootstrap';
$em = $container->get('EntityManager');

echo "\nregisterDoctrineTypeMapping";


// custom datatypes (not mapped for reverse engineering)
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('_int4', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('_text', 'string'); 
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('_uuid', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('bit', 'string');

echo "\nfetch metadata";

// fetch metadata
$driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
                $em->getConnection()->getSchemaManager()
);

echo "\nget tables";
echo "\n";
//echo print_r($em->getConnection()->getSchemaManager()->listTableDetails('visitor_management.visits'), true);
//return;

//echo print_r($em->getConnection()->getSchemaManager()->listTables(), true);
//return;

$specificTables = [
    $em->getConnection()->getSchemaManager()->listTableDetails('visitor_management.visits'),
    $em->getConnection()->getSchemaManager()->listTableDetails('visitor_management.visits_has_people'),
    $em->getConnection()->getSchemaManager()->listTableDetails('visitor_management.visits_has_students'),
    $em->getConnection()->getSchemaManager()->listTableDetails('prepared.teams')
];

$driver->setTables($specificTables, []);

$em->getConfiguration()->setMetadataDriverImpl($driver);
$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory($em);
$cmf->setEntityManager($em);
$classes = $driver->getAllClassNames();
$metadata = $cmf->getAllMetadata();
$generator = new Doctrine\ORM\Tools\EntityGenerator();
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);
$generator->generate($metadata, APP_ROOT . '/src/Entities');
print 'Done!';