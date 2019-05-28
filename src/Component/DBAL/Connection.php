<?php

namespace Gestione\Component\DBAL;

use Doctrine\DBAL\Driver\Connection as DoctrineConnection;
use Doctrine\DBAL\ParameterType;

class Connection implements ConnectionInterface
{
    protected $connection;

    public function __construct(DoctrineConnection $connection)
    {
        $this->connection = $connection;
    }

    public function prepare($prepareString)
    {
        return $this->connection->prepare($prepareString);
    }

    public function query()
    {
        $args = func_get_args();

        return $this->connection->query(...$args);
    }

    public function quote($input, $type = ParameterType::STRING)
    {
        return $this->connection->quote($input, $type = ParameterType::STRING);
    }

    public function exec($statement)
    {
        return $this->connection->exec($statement);
    }

    public function lastInsertId($name = null)
    {
        return $this->connection->lastInsertId($name = null);
    }

    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    public function errorCode()
    {
        return $this->connection->errorCode();
    }

    public function errorInfo()
    {
        return $this->connection->errorInfo();
    }
}
