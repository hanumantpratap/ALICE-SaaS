<?php
namespace App\Classes;
use PDO;
use PDOException;

class DistrictDatabaseConnection extends DatabaseConnection {
    function __construct($secureId = null, $config, $logger) {
        $this->host = $config['host'];
        $this->dbName = $config['dbname'] . '_' . $secureId;
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->port = $config['port'];
        $this->logger = $logger;
        
        $this->create();
    }
}
