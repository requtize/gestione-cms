<?php

namespace Gestione\Cms\Routing;

interface MatchInterface
{
    public function isHit(): bool;
    public function setController(string $controller): self;
    public function getController(): ?string;
    public function getAttributes(): array;
    public function setAttribute(string $name, $value): self;
    public function hasAttribute(string $name): bool;
    public function removeAttribute(string $name): self;
    public function mergeAttributes(array $attributes): array;
}
