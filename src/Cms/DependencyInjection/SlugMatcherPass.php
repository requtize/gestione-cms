<?php

namespace Gestione\Cms\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SlugMatcherPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $eventManager = $container->getDefinition('cms.routing.matcher');

        foreach($container->findTaggedServiceIds('page.slug_matcher', true) as $id => $tags)
        {
            $eventManager->addMethodCall('addMatcher', [ new Reference($id) ]);
        }
    }
}
