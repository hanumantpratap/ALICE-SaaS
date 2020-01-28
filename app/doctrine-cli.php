<?php
declare(strict_types=1);

// doctrine-cli.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

/*
 *  @var Container $container
*/
$container = include_once __DIR__ . '/bootstrap.php';

return ConsoleRunner::createHelperSet($container[EntityManager::class]);
