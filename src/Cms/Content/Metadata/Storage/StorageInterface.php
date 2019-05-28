<?php

namespace Gestione\Cms\Content\Metadata\Storage;

interface StorageInterface
{
    public function get($contentId, string $name, $default = null);
    public function getMany($contentId, array $names): array;
    public function set($contentId, string $name, $value);
    public function delete($contentId, string $name);
    public function getContentType(): string;
}
