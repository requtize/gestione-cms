<?php

namespace Gestione\Cms;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Gestione\Component\HttpFoundation\Request;
use Gestione\Cms\Http\Controller\DefaultController;

class NotFoundHttpExceptionHandler
{
    protected $httpKernel;

    public function __construct(HttpKernel $httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if($event->getException() instanceof NotFoundHttpException === false)
            return;

        $request = clone $event->getRequest();
        $request->attributes->set('_controller', DefaultController::class.'::notFound404');

        $response = $this->httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);

        $event->setResponse($response);
    }
}
