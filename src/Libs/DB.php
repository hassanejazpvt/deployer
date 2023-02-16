<?php

namespace Contrive\Deployer\Libs;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

class DB
{
    protected $conn;
    protected $queryBuilder;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => 'deployer',
            'user' => 'root',
            'password' => '123456',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];

        $this->conn = DriverManager::getConnection($connectionParams);
        $this->queryBuilder = $this->conn->createQueryBuilder();
    }

    /**
     * @return Connection
     */
    public function getConn() : Connection
    {
        return $this->conn;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder() : QueryBuilder
    {
        return $this->queryBuilder;
    }
}
