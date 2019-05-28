<?php

namespace Gestione\Component\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\Common\EventManager;

class DatabaseFactory
{
    public static function createDatabase(EventManager $eventManager)
    {
        $driver = DriverManager::getConnection([
            'dbname'   => getenv('GESTIONE_DB_NAME'),
            'user'     => getenv('GESTIONE_DB_USER'),
            'password' => getenv('GESTIONE_DB_PASS'),
            'host'     => getenv('GESTIONE_DB_HOST'),
            'port'     => getenv('GESTIONE_DB_PORT'),
            'driver'   => getenv('GESTIONE_DB_DRIVER'),
            'charset'  => 'utf8'
        ], new Configuration, $eventManager);

        return new Database(new Connection($driver), getenv('GESTIONE_DB_PREFIX'));
    }
}
