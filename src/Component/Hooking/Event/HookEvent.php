<?php

namespace Gestione\Component\Hooking\Event;

use Symfony\Component\EventDispatcher\Event;

abstract class HookEvent extends Event
{
    protected $name;
    protected $arguments = [];
    protected $content;

    public function __construct(string $name, array $arguments = [], $content = null)
    {
        $this->name = $name;
        $this->setArguments($arguments);
        $this->setContent($content);
    }

    abstract public function getType(): string;

    public function buildEventName(): string
    {
        return 'hooking.'.$this->getType().'.'.$this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setContent($content): HookEvent
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setArguments($arguments): HookEvent
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArgument(string $name, $value)
    {
        $this->arguments[$name] = $arguments;

        return $this;
    }

    public function getArgument(string $name, $default = null)
    {
        return isset($this->arguments[$name]) ? $this->arguments[$name] : $default;
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }
}
