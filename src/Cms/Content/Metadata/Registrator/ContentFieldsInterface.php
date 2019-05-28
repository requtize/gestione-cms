<?php

namespace Gestione\Cms\Content\Metadata\Registrator;

interface ContentFieldsInterface
{
    public function add(array $field): self;
    public function remove(string $field): self;
    public function empty(): self;
    public function count(): int;
    public function getNames(): array;
    public function all(): array;
}
