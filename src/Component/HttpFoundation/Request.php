<?php

namespace Gestione\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest
{
    protected $segments = [];

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->setIsAdmin(false);
        $this->setDefaultLocale('en-US');
        //$this->setIsDefaultLocale(false);
        //$this->setLocaleUrlPrefix('');
    }

    public function countSegments()
    {
        $this->prepareSegments();

        return count($this->segments);
    }

    public function getSegments()
    {
        $this->prepareSegments();

        return $this->segments;
    }

    public function getSegment($index)
    {
        $this->prepareSegments();

        return isset($this->segments[$index - 1]) ? $this->segments[$index - 1] : null;
    }

    public function isAdmin()
    {
        return $this->attributes->get('is-admin');
    }

    public function setIsAdmin($is)
    {
        $this->attributes->set('is-admin', $is);

        return $this;
    }

    public function hasLocale()
    {
        return !! $this->locale;
    }

    public function isAjax()
    {
        return $this->isXmlHttpRequest();
    }

    public function getUriForPath($path)
    {
        return parent::getUriForPath($this->getDirectory().$path);
    }

    public function getPathInfo()
    {
        $directory = $this->getDirectory();

        if($directory)
            return str_replace($directory, '', parent::getPathInfo());
        else
            return parent::getPathInfo();
    }

    public function getDirectory()
    {
        $result = $this->getScriptName();
        $result = str_replace('/public/', '/', $result);
        $result = str_replace('index.php', '', $result);
        $result = trim($result, '/');

        if($result === '')
            return '';

        return '/'.$result;
    }

    protected function prepareSegments()
    {
        if($this->segments === [])
        {
            $this->segments = explode('/', $this->getPathInfo());
            array_shift($this->segments);
        }
    }
}
