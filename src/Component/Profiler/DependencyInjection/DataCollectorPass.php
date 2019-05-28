<?php

namespace Gestione\Component\Profiler\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DataCollectorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $profiler = $container->getDefinition('profiler');

        foreach($container->findTaggedServiceIds('profiler.data_collector', true) as $id => $tags)
        {
            $profiler->addMethodCall('add', [ new Reference($id) ]);
        }
    }
}
