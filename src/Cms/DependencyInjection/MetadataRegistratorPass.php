<?php

namespace Gestione\Cms\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class MetadataRegistratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition('metadata.registry');

        foreach($container->findTaggedServiceIds('metadata.registrator', true) as $id => $tags)
        {
            $registry->addMethodCall('addRegistrator', [ new Reference($id) ]);
        }
    }
}
