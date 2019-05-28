<?php

namespace Gestione\Cms\Http\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Gestione\Framework\Controller\AbstractController;

class HomepageController extends AbstractController
{
    public function homepage(Request $request)
    {
        return $this->render([
            '@theme/homepage.tpl',
            '@cms/homepage.tpl',
        ]);
    }
}
