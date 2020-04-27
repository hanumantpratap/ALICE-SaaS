<?php
namespace App\Classes;
use PDO;
use PDOException;

class DatabaseConnection {
    private $host;
    private $dbName;
    private $user;
    private $password;
    private $port;

    private $pdo = null;

    function __construct($config, $logger) {
        $this->host = $config['host'];
        $this->dbName = $config['dbname'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->port = $config['port'];
        $this->logger = $logger;

        $this->create();
    }

    public function create() {
        $conStr = 'pgsql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbName.';user='.$this->user.';password='.$this->password;
        $this->logger->info('constr:' . $conStr);

        try {
            $this->pdo = new PDO($conStr);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function run($sql, $args = NULL)
    {
        try {
            if (!$args)
            {
                return $this->pdo->query($sql);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        }
        catch(Exception $e) {
            throw $e;
        }
    }

    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }

    public function rollBack() {
        $this->pdo->rollBack();
    }

    public function commit() {
        $this->pdo->commit();
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
