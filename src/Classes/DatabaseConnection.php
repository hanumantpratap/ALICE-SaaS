<?php
namespace App\Classes;

class DatabaseConnection {
    private $host;
    private $dbName;
    private $user;
    private $password;
    private $port;

    private $pdo = null;

    function __construct($secureId = null, $config, $logger) {
        $this->host = $config['host'];
        $this->dbName = ($secureId != null ? $config['database'] . '_' . $secureId : $config['database']);
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->port = $config['port'];

        $conStr = 'pgsql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbName.';user='.$this->user.';password='.$this->password;
        $logger->info('constr:' . $conStr);

        try {
            $this->pdo = new \PDO($conStr);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function run($sql, $args = NULL)
    {
        try {
            if (!$args)
            {
                return $this->query($sql);
            }
            $stmt = $this->prepare($sql);
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
