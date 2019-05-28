<?php

namespace Gestione\Component\Hooking;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Gestione\Component\Hooking\Event\HookEvent;
use Gestione\Component\Hooking\Event\HookActionEvent;
use Gestione\Component\Hooking\Event\HookFilterEvent;

class Hooker implements HookerInterface
{
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /*public function registerSubscriber(SubscriberInterface $subscriber)
    {
        $subscriber->subscribeHooks($this);

        return $this;
    }*/

    public function registerAction($action, callable $callable, $priority = 0)
    {
        $this->dispatcher->addListener('hooker.action.'.$action, $callable, $priority);

        return $this;
    }

    public function registerFilter($filter, callable $callable, $priority = 0)
    {
        $this->dispatcher->addListener('hooker.filter.'.$filter, $callable, $priority);

        return $this;
    }

    public function doAction($action, array $arguments = [])
    {
        if($action instanceof HookActionEvent)
            $event = $action;
        else
            $event = new HookActionEvent($action, $arguments);

        $this->hook($event);

        return $event->getContent();
    }

    public function doFilter($filter, $content = null, array $arguments = [])
    {
        if($filter instanceof HookFilterEvent)
            $event = $filter;
        else
            $event = new HookFilterEvent($filter, $arguments, $content);

        $this->hook($event);

        return $event->getContent();
    }

    protected function hook(HookEvent $event)
    {
        $this->dispatcher->dispatch($event->buildEventName(), $event);
    }
}
