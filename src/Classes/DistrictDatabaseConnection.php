<?php
namespace App\Classes;

class DistrictDatabaseConnection extends DatabaseConnection {
    function __construct($secureId, $config, $logger) {
        $this->host = $config['host'];
        $this->dbName = $config['dbname'] . '_' . $secureId;
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->port = $config['port'];
        $this->logger = $logger;
        
        $this->create();
    }
}
