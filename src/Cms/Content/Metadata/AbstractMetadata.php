<?php

namespace Gestione\Cms\Content\Metadata;

use Gestione\Cms\Content\Metadata\Syncer\SyncerInterface;

abstract class AbstractMetadata implements MetadataInterface
{
    protected $syncer;
    protected $contentId;
    protected $metadata = [];
    protected $isEmpty = true;
    protected $newEntries = [];

    public function __construct($contentId, SyncerInterface $syncer)
    {
        $this->setContentId($contentId);
        $this->setSyncer($syncer);
    }

    public function setContentId($contentId): MetadataInterface
    {
        $this->contentId = $contentId;

        return $this;
    }
    public function getContentId()
    {
        return $this->contentId;
    }

    public function setSyncer(SyncerInterface $syncer): MetadataInterface
    {
        $this->syncer = $syncer;

        return $this;
    }

    public function getSyncer(): SyncerInterface
    {
        return $this->syncer;
    }

    public function sync()
    {
        if($this->syncer === null)
            return;

        if($this->isEmpty)
        {
            $this->syncer->pull($this->contentId, $this);
            $this->isEmpty = false;
        }

        if($this->newEntries !== [])
        {
            $this->syncer->push($this->contentId, $this, $this->newEntries);
            $this->newEntries = [];
        }
    }

    public function get(string $name, $default = null)
    {
        $this->sync();

        return isset($this->metadata[$name]) ? $this->metadata[$name] : $default;
    }

    public function set(string $name, $value): MetadataInterface
    {
        $this->metadata[$name] = $value;

        $this->addNewEntry($name);

        return $this;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->metadata);
    }

    public function add(array $metadata)
    {
        foreach($metadata as $key => $val)
        {
            $this->set($key, $val);
        }
    }

    public function all(): array
    {
        $this->sync();

        return $this->metadata;
    }

    public function hasNewEntries(): bool
    {
        return $this->newEntries !== [];
    }

    public function addNewEntry(string $entry): MetadataInterface
    {
        $this->newEntries[] = $entry;

        return $this;
    }

    public function resetNewEntry(): MetadataInterface
    {
        $this->newEntries = [];

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->isEmpty;
    }

    public function setIsEmpty(bool $bool): MetadataInterface
    {
        $this->isEmpty = $bool;

        return $this;
    }
}
