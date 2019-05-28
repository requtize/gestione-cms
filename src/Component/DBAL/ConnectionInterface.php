<?php

namespace Gestione\Component\DBAL;

use Doctrine\DBAL\ParameterType;

interface ConnectionInterface
{
    public function prepare($prepareString);

    public function query();

    public function quote($input, $type = ParameterType::STRING);

    public function exec($statement);

    public function lastInsertId($name = null);

    public function beginTransaction();

    public function commit();

    public function rollBack();

    public function errorCode();

    public function errorInfo();
}
