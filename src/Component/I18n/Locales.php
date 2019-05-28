<?php

namespace Gestione\Component\I18n;

use ArrayIterator;
use IteratorAggregate;

class Locales implements IteratorAggregate
{
    protected $locales = [];

    public function __construct()
    {
        $this->add(new Locale('pl-PL', 'pl'));
        $this->add(new Locale('en-US', 'en'));
    }

    public function add(LocaleInterface $locale): self
    {
        $this->locales[$locale->getCode()] = $locale;

        return $this;
    }

    public function all(): array
    {
        return $this->locales;
    }

    public function getBySlug(string $slug): ?Locale
    {
        foreach($this->locales as $locale)
        {
            if($locale->getSlug() === $slug)
            {
                return $locale;
            }
        }

        return null;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->locales);
    }
}
