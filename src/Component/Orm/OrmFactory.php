<?php

namespace Gestione\Component\Orm;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Database;
use Gestione\Component\HttpKernel\Module\ModulesCollection;

class OrmFactory
{
    public static function createOrm($environment, ModulesCollection $modules, Database $database)
    {
        $directories = [];

        foreach($modules as $module)
        {
            $directories[] = $module->getPath();
        }

        $config = Setup::createAnnotationMetadataConfiguration($directories, $environment == 'dev');

        //$tablePrefix = new \DoctrineExtensions\TablePrefix(getenv('GESTIONE_DB_PREFIX'));
        //$evm->addEventListener(Events::loadClassMetadata, $tablePrefix);

        return EntityManager::create($database->getConnection(), $config, $database->getConnection()->getEventManager());
    }
}
