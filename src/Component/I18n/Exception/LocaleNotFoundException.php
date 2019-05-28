<?php

namespace Gestione\Component\I18n\Exception;

use Gestione\Component\I18n\Locales;

class LocaleNotFoundException extends I18nException
{
    public static function defaultLocaleNotFound($locale, Locales $locales)
    {
        return new self('Default locale ('.$locale.') not found in available locales ('.static::createAvaibaleLocalesList($locales).').');
    }

    public static function localeNotFound($locale, Locales $locales)
    {
        return new self('Locale ('.$locale.') not found in available locales ('.static::createAvaibaleLocalesList($locales).').');
    }

    public function createAvaibaleLocalesList(Locales $locales)
    {
        $list = [];

        foreach($locales as $locale)
        {
            $list[] = $locale->getCode();
        }

        return implode(', ', $list);
    }
}
