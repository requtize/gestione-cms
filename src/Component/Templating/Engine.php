<?php

namespace Gestione\Component\Templating;

use Twig\Environment;
use Gestione\Component\Templating\Exception\ViewNotFoundException;

class Engine
{
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(View $view)
    {
        $loader = $this->twig->getLoader();
        $existingView = null;

        foreach($view->getViews() as $viewName)
        {
            if($loader->exists($viewName))
            {
                $existingView = $viewName;
                break;
            }
        }

        if($existingView === null)
            throw ViewNotFoundException::anyViewNotFound($view->getViews());

        return $this->twig->render($existingView, $view->getData());
    }
}
