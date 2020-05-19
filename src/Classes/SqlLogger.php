<?php
namespace App\Classes;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Logging\SQLLogger as DoctrineLoggerInterface;

class SqlLogger implements DoctrineLoggerInterface {

    function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function startQuery($sql, ?array $params = null, ?array $types = null) {
        $this->logger->info($sql, $params ?? array());
    }

    public function stopQuery() {
        $this->logger->info("Query stopped");
    }
}