<?php
declare(strict_types=1);

// doctrine-cli.php
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/*
 *  @var Container $container
*/
$container = include_once __DIR__ . '/doctrine-bootstrap';

return ConsoleRunner::createHelperSet($container->get('EntityManager'));
