<?php

namespace Gestione\Component\I18n;

class Locale implements LocaleInterface
{
    protected $code;
    protected $language;
    protected $region;
    protected $slug;

    public function __construct($code, $slug = null)
    {
        $this->setLocale($code);
        $this->setSlug($slug ? $slug : $code);
    }

    public function __toString()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode(string $code): LocaleInterface
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * {@inheritdoc}
     */
    public function setLanguage(string $language): LocaleInterface
    {
        $this->language = $language;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * {@inheritdoc}
     */
    public function setRegion(string $region): LocaleInterface
    {
        $this->region = $region;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug(string $slug): LocaleInterface
    {
        $this->slug = $slug;

        return $this;
    }

    protected function setLocale($locale)
    {
        list($this->language, $this->region) = static::parseLocale($locale);

        $this->code = $this->language.'-'.$this->region;

        return $this;
    }

    public static function parseLocale($locale)
    {
        if(strpos($locale, '-') === false)
        {
            $locale = substr($locale, 0, 2);

            return [
                strtolower($locale),
                strtoupper($locale)
            ];
        }
        else
        {
            $part = explode('-', $locale);

            return [
                strtolower(substr($part[0], 0, 2)),
                strtoupper(substr($part[1], 0, 2))
            ];
        }
    }
}
