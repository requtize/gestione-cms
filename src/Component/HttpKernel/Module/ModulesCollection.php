<?php

namespace Gestione\Component\HttpKernel\Module;

use IteratorAggregate;
use ArrayIterator;

class ModulesCollection implements IteratorAggregate
{
    protected $modules = [];

    public function empty()
    {
        $this->modules = [];

        return $this;
    }

    public function add(Module $module)
    {
        $this->modules[$module->getName()] = $module;

        return $this;
    }

    public function exists($name)
    {
        return isset($this->modules[$name]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->modules);
    }
}
