<?php

namespace Gestione\Cms\Content\Metadata\Syncer;

use Gestione\Cms\Content\Metadata\MetadataInterface;

class AnonymousSyncer implements SyncerInterface
{
    protected $pull;
    protected $push;

    public function __construct(callable $pull, callable $push);
    {
        $this->pull = $pull;
        $this->push = $push;
    }

    public function pull($contentId, MetadataInterface $metadata)
    {
        ($this->pull)($contentId, $metadata);
    }

    public function push($contentId, MetadataInterface $metadata, array $entries)
    {
        ($this->push)($contentId, $metadata, $entries);
    }
}
