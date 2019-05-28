<?php

namespace Gestione\Component\I18n\Request;

use Gestione\Component\I18n\Locales;
use Gestione\Component\I18n\Locale;
use Gestione\Component\HttpFoundation\Request;

class LocaleGuesser
{
    protected $locales;

    public function __construct(Locales $locales)
    {
        $this->locales = $locales;
    }

    public function guessFromPath($path, $default = null): ?Locale
    {
        $segments = explode('/', $path);

        if(isset($segments[1]) === false)
            return null;

        foreach($this->locales as $locale)
        {
            if($locale->getSlug() == $segments[1])
            {
                return $locale;
            }
        }

        return null;
    }

    public function guessFromRequest($path, $default = null): ?Locale
    {

    }
}
