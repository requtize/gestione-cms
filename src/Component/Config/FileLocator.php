<?php

namespace Gestione\Component\Config;

use Symfony\Component\Config\FileLocator as BaseFileLocator;

class FileLocator extends BaseFileLocator
{
    public function setPaths(array $paths)
    {
        $this->paths = $paths;

        return $this;
    }
}
