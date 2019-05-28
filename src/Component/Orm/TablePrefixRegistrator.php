<?php

namespace Gestione\Component\Orm;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class TablePrefixRegistrator
{
    protected $prefix;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if(! $classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName)
        {
            $classMetadata->setPrimaryTable([
                'name' => $this->prefix.$classMetadata->getTableName()
            ]);
        }

        foreach($classMetadata->getAssociationMappings() as $fieldName => $mapping)
        {
            if($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide'])
            {
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix.$mapping['joinTable']['name'];
            }
        }
    }
}
