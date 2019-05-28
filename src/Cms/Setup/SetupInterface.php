<?php

namespace Gestione\Cms\Setup;

interface SetupInterface
{
    public function getSetuppers(): array;
    public function setSetuppers(array $setuppers = []): SetupInterface;
    public function callSetuppers($argument);
}
