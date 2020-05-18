<?php
declare(strict_types=1);
include_once __DIR__ . '/vendor/autoload.php';
define('APP_ROOT', realpath(__DIR__ . '/'));

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

/*
 *  @var Container $container
*/
$container = include_once __DIR__ . '/app/doctrine-bootstrap.php';
$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
