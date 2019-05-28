<?php

namespace Gestione\Framework\Routing\Matcher;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Gestione\Component\HttpKernel\Module\ModulesCollection;

class StaticRoutingMatcher
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if($event->getRequest()->attributes->has('_controller'))
            return;

        $this->router->getContext()->fromRequest($event->getRequest());

        try
        {
            $route = $this->router->matchRequest($event->getRequest());
        }
        catch(ResourceNotFoundException $e)
        {
            return;
        }

        $event->getRequest()->attributes->add($route);
    }
}
