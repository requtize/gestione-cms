<?php

namespace Gestione\Component\DBAL;

class Database implements DatabaseInterface
{
    protected $connection;
    protected $tablePrefix;

    public function __construct(ConnectionInterface $connection, $tablePrefix = '')
    {
        $this->connection  = $connection;
        $this->tablePrefix = $tablePrefix;
    }

    public function getTablePrefix(): string
    {
        return $this->tablePrefix;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function query(string $sql, array $binds = [])
    {
        $statement = $this->connection->prepare($this->prepareQueryWithPrefix($sql));

        foreach($binds as $key => $val)
            $statement->bindValue($key, $val);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function prepare(string $sql)
    {
        return $this->connection->prepare($this->prepareQueryWithPrefix($sql));
    }

    public function quote($value)
    {
        return $this->connection->quote($value);
    }

    public function prepareQueryWithPrefix(string $query)
    {
        return str_replace('#__', $this->getTablePrefix(), $query);
    }
}
