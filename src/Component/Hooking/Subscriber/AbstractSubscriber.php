<?php

namespace Gestione\Component\Hooking\Subscriber;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Gestione\Component\Hooking\Configuration;

abstract class AbstractSubscriber implements SubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    abstract public function configure(Configuration $configuration);
}
