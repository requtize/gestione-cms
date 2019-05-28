<?php

namespace Gestione\Component\Profiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Gestione\Component\HttpKernel\Module\Module;
use Gestione\Component\Profiler\DependencyInjection\DataCollectorPass;

class ProfilerModule extends Module
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DataCollectorPass);
    }
}
