<?php

namespace Gestione\Framework\Controller;

use Gestione\Component\Templating\View;
use Gestione\Cms\Content\Page\PageInterface;

trait ControllerTrait
{
    protected function get($name)
    {
        return $this->container->get($name);
    }

    protected function has($name)
    {
        return $this->container->has($name);
    }

    protected function getDatabase()
    {
        return $this->container->get('database');
    }

    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm');
    }

    protected function generateSlug($input)
    {
        return $this->container->get('slugger')->generate($input);
    }

    protected function getPageManager()
    {
        return $this->container->get('page.manager');
    }

    protected function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    protected function getHooker()
    {
        return $this->container->get('hooker');
    }

    protected function registerAction($action, callable $callable, $priority = 0)
    {
        return $this->container->get('hooker')->registerAction($action, $callable, $priority);
    }

    protected function registerFilter($filter, callable $callable, $priority = 0)
    {
        return $this->container->get('hooker')->registerFilter($filter, $callable, $priority);
    }

    protected function doAction($action, array $arguments = [])
    {
        return $this->container->get('hooker')->doAction($action, $arguments);
    }

    protected function doFilter($filter, $content = null, array $arguments = [])
    {
        return $this->container->get('hooker')->doFilter($filter, $content, $arguments);
    }

    protected function renderView($view = null, array $data = [])
    {
        return $this->container->get('templating')->render(new View($view, $data));
    }

    protected function renderFirstLocatedView(array $views, array $data = [])
    {
        return $this->container->get('templating')->render(new View($views, $data));
    }

    protected function render($view = null, array $data = [])
    {
        return new View($view, $data);
    }

    protected function renderFirstLocated(array $views, array $data = [])
    {
        return new View($views, $data);
    }

    protected function setupPage(PageInterface $page)
    {
        return $this->container->get('page.setup')->setup($page);
    }

    protected function setupTaxonomy(TaxonomyInterface $taxonomy)
    {
        return $this->container->get('taxonomy.setup')->setup($taxonomy);
    }
}
