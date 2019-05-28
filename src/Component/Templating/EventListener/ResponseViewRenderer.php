<?php

namespace Gestione\Component\Templating\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;
use Gestione\Component\Templating\Engine;
use Gestione\Component\Templating\View;

class ResponseViewRenderer
{
    protected $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if(! $event->getControllerResult() instanceof View)
            return;

        $event->setResponse(new Response($this->engine->render($event->getControllerResult())));
    }
}
