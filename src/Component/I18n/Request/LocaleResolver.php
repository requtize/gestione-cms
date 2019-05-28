<?php

namespace Gestione\Component\I18n\Request;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Gestione\Component\HttpFoundation\Request;
use Gestione\Component\I18n\Locales;
use Gestione\Component\I18n\Request\LocaleGuesser;
use Gestione\Component\I18n\Exception\LocaleNotFoundException;

class LocaleResolver
{
    protected $locales;
    protected $guesser;

    public function __construct(Locales $locales, LocaleGuesser $guesser)
    {
        $this->locales = $locales;
        $this->guesser = $guesser;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->resolveLocale($event->getRequest());
    }

    public function resolveLocale(Request $request)
    {
        if(is_object($request->getDefaultLocale()) && $request->hasLocale())
            return;

        $attributeLocale = $request->attributes->get('_locale');

        if(! $attributeLocale)
            $attributeLocale = $request->getDefaultLocale();

        foreach($this->locales as $locale)
        {
            if($locale->getSlug() === $attributeLocale)
                $request->setLocale($locale);

            if($locale == $request->getDefaultLocale() || $locale->getSlug() === $request->getDefaultLocale())
                $request->setDefaultLocale($locale);
        }

        if($request->hasLocale() === false)
        {
            $locale = $this->guesser->guessFromPath($request->getPathInfo());

            if($locale)
            {
                $request->setLocale($locale);
            }
        }

        if(is_object($request->getDefaultLocale()) === false)
            throw LocaleNotFoundException::defaultLocaleNotFound($request->getDefaultLocale(), $this->locales);

        if(is_object($request->getLocale()) === false)
            throw LocaleNotFoundException::localeNotFound($request->getLocale(), $this->locales);
    }
}
