<?php

namespace Gestione\Cms\Routing;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Gestione\Component\HttpFoundation\Request;
use Gestione\Component\I18n\Locales;
use Gestione\Cms\Routing\SlugMatcherInterface;

class Matcher
{
    protected $locales;
    protected $matchers = [];

    public function __construct(Locales $locales)
    {
        $this->locales = $locales;
    }

    public function addMatcher(SlugMatcherInterface $matcher)
    {
        $this->matchers[] = $matcher;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if($event->getRequest()->attributes->has('_controller'))
            return;

        $path = $event->getRequest()->getPathInfo();

        /**
         * Force trailing slash.
         */
        if(substr($path, -1, 1) !== '/')
            return;

        $segments = explode('/', $path);
        $attributes = [];

        $pageSlug = null;
        $paginationPage = null;

        if(isset($segments[1]))
        {
            $locale = $this->locales->getBySlug($segments[1]);

            if($locale && $event->getRequest()->getDefaultLocale() != $locale)
            {
                $attributes['_locale'] = $segments[1];

                if($path !== "/{$segments[1]}/")
                {
                    if(isset($segments[2]))
                        $pageSlug = $segments[2];
                    if(isset($segments[3]))
                        $paginationPage = $segments[3];
                }
            }
            else
            {
                if($path !== '/')
                {
                    if(isset($segments[1]))
                        $pageSlug = $segments[1];
                    if(isset($segments[2]))
                        $paginationPage = $segments[2];
                }
            }
        }
        else
        {
            return;
        }

        if($paginationPage)
        {
            if(filter_var($paginationPage, FILTER_VALIDATE_INT))
            {
                $attributes['pagination.page'] = $paginationPage;
            }
            /**
             * For now, we only accept pagination on second segment of path.
             * Otherwise we block any pages.
             */
            else
            {
                return;
            }
        }

        if($pageSlug)
        {
            foreach($this->matchers as $matcher)
            {
                $match = $matcher->match($pageSlug);

                if($match->isHit() === false)
                {
                    continue;
                }

                if(isset($attributes['pagination.page']))
                {
                    if($matcher->supportsPagination() === false)
                    {
                        continue;
                    }
                }

                $attributes = $match->mergeAttributes($attributes);
                $attributes['slug'] = $pageSlug;
            }
        }

        if(isset($attributes['pagination.page']))
            $attributes['pagination.page'] = 1;

        $event->getRequest()->attributes->add($attributes);
    }
}
