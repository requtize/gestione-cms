<?php

namespace Gestione\Framework\Controller;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerControllerResolver
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if(isset($controller[0]))
            $this->resolveControllerContainer($controller[0]);
    }

    public function resolveControllerContainer($controller)
    {
        if(is_object($controller) === false)
            return;

        if($controller instanceof ContainerAwareInterface)
        {
            $controller->setContainer($this->container);
        }
    }
}
