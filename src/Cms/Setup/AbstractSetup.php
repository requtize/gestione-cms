<?php

namespace Gestione\Cms\Setup;

abstract class AbstractSetup implements SetupInterface
{
    protected $setuppers = [];

    public function getSetuppers(): array
    {
        return $this->setuppers;
    }

    public function setSetuppers(array $setuppers = []): SetupInterface
    {
        $this->setuppers = [];

        foreach($setuppers as $setupper)
        {
            $this->addSetuper($setupper);
        }

        return $this;
    }

    public function callSetuppers($argument)
    {
        foreach($this->setuppers as $setupper)
        {
            $setupper->setup($argument);
        }
    }
}
