<?php

namespace Gestione\Component\Templating\Exception;

class ViewNotFoundException extends Exception
{
    public static function anyViewNotFound(array $searchedFor)
    {
        return new self("Any view not found. Searched for: '".implode("', '", $searchedFor)."'.");
    }
}
