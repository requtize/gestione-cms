<?php

namespace Gestione\Cms\Routing;

class Match implements MatchInterface
{
    protected $attributes = [];

    public function isHit(): bool
    {
        return $this->getAttribute('_controller') !== null;
    }

    public function setController(string $controller): MatchInterface
    {
        $this->setAttribute('_controller', $controller);

        return $this;
    }

    public function getController(): ?string
    {
        return $this->getAttribute('_controller');
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttribute(string $name, $value): MatchInterface
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getAttribute(string $name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    public function removeAttribute(string $name): MatchInterface
    {
        unset($this->attributes[$name]);

        return $this;
    }

    public function mergeAttributes(array $attributes): array
    {
        return array_merge($this->attributes, $attributes);
    }
}
