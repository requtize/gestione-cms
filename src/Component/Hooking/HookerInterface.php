<?php

namespace Gestione\Component\Hooking;

interface HookerInterface
{
    /*public function registerSubscriber(SubscriberInterface $subscriber);*/

    public function registerAction($action, callable $callable, $priority = 0);

    public function registerFilter($filter, callable $callable, $priority = 0);

    public function doAction($action, array $arguments = []);

    public function doFilter($filter, $content = null, array $arguments = []);
}
