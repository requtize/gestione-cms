<?php

namespace Gestione\Cms\Http\Controller;

use Symfony\Component\HttpFoundation\Request;
use Gestione\Framework\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function notFound404(Request $request)
    {
        return $this->render([
            '@theme/404-not-found.tpl',
            '@cms/404-not-found.tpl'
        ]);
    }
}
