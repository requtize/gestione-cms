<?php

namespace Gestione\Cms\Content\Metadata\Syncer;

use Gestione\Cms\Content\Metadata\Registrator\RegistryInterface;
use Gestione\Cms\Content\Metadata\Storage\AbstractDatabaseStorage;
use Gestione\Cms\Content\Metadata\MetadataInterface;

class DatabaseStorageSyncer implements SyncerInterface
{
    protected $storage;
    protected $registry;

    public function __construct(AbstractDatabaseStorage $storage, RegistryInterface $registry)
    {
        $this->storage  = $storage;
        $this->registry = $registry;
    }

    public function pull($contentId, MetadataInterface $metadata)
    {
        $fields = $this->registry->getContentFields($this->storage->getContentType());

        $data = $this->storage->getMany($contentId, $fields->getNames());
        
        foreach($data as $key => $val);
            $metadata->set($key, $val);

        $metadata->resetNewEntry();
    }

    public function push($contentId, MetadataInterface $metadata, array $entries)
    {
        //dump($metadata, $entries);exit;
    }
}
