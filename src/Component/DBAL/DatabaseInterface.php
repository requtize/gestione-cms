<?php

namespace Gestione\Component\DBAL;

interface DatabaseInterface
{
    public function getConnection(): ConnectionInterface;

    public function query(string $sql, array $binds = []);

    public function prepare(string $sql);

    public function quote($value);

    public function getTablePrefix(): string;
}
