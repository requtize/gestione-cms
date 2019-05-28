<?php

namespace Gestione\Component\I18n;

interface LocaleInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param string $code
     *
     * @return self
     */
    public function setCode(string $code): LocaleInterface;

    /**
     * @return string
     */
    public function getLanguage(): string;

    /**
     * @param string $language
     *
     * @return self
     */
    public function setLanguage(string $language): LocaleInterface;

    /**
     * @return string
     */
    public function getRegion(): string;

    /**
     * @param string $region
     *
     * @return self
     */
    public function setRegion(string $region): LocaleInterface;

    /**
     * @return string
     */
    public function getSlug(): string;

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug(string $slug): LocaleInterface;
}
