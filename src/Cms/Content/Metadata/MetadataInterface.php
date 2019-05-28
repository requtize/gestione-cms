<?php

namespace Gestione\Cms\Content\Metadata;

use Gestione\Cms\Content\Metadata\Syncer\SyncerInterface;

interface MetadataInterface
{
    public function setContentId($contentId): self;
    public function getContentId();
    public function setSyncer(SyncerInterface $syncer): self;
    public function getSyncer(): SyncerInterface;
    public function sync();
    public function get(string $name, $default = null);
    public function set(string $name, $value): self;
    public function has(string $name): bool;
    public function add(array $metadata);
    public function all(): array;
}
