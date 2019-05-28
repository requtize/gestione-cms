<?php

namespace Gestione\Cms\Content\Metadata\Syncer;

use Gestione\Cms\Content\Metadata\MetadataInterface;

interface SyncerInterface
{
    public function pull($contentId, MetadataInterface $metadata);
    public function push($contentId, MetadataInterface $metadata, array $entries);
}
