<?php

namespace Gestione\Component\I18n;

use Gestione\Component\HttpFoundation\Request;
use Gestione\Component\I18n\Locales;

class LocaleFromRequestFactory
{
    public static function createLocale(Locales $locales, Request $request)
    {
        return $request->getLocale();
    }
}
