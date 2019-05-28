<?php

namespace Gestione\Component\Orm\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RegisterEventListenersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $eventManager = $container->getDefinition('doctrine.event_manager');

        foreach($this->findAndSortTags('doctrine.event_listener', $container) as $taggedListener)
        {
            list($id, $tag) = $taggedListener;

            if(isset($tag['event']) === false)
            {
                throw new InvalidArgumentException(sprintf('Doctrine event listener "%s" must specify the "event" attribute.', $id));
            }

            $eventManager->addMethodCall('addEventListener', [ $tag['event'], new Reference($id) ]);
        }
    }

    private function findAndSortTags($tagName, ContainerBuilder $container)
    {
        $sortedTags = [];

        foreach($container->findTaggedServiceIds($tagName, true) as $serviceId => $tags)
        {
            foreach($tags as $attributes)
            {
                $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;

                $sortedTags[$priority][] = [$serviceId, $attributes];
            }
        }

        if($sortedTags)
        {
            krsort($sortedTags);
            $sortedTags = array_merge(...$sortedTags);
        }

        return $sortedTags;
    }
}
