<?php

namespace Gestione\Component\Hooking\Subscriber;

use Gestione\Component\Hooking\Configuration;

interface SubscriberInterface
{
    public function configure(Configuration $configuration);
}
