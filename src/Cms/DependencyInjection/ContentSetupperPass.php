<?php

namespace Gestione\Cms\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ContentSetupperPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $setup = $container->getDefinition('page.setup');

        foreach($container->findTaggedServiceIds('page.setupper', true) as $id => $tags)
        {
            $setup->addMethodCall('addSetupper', [ new Reference($id) ]);
        }

        $setup = $container->getDefinition('taxonomy.setup');

        foreach($container->findTaggedServiceIds('taxonomy.setupper', true) as $id => $tags)
        {
            $setup->addMethodCall('addSetupper', [ new Reference($id) ]);
        }
    }
}
