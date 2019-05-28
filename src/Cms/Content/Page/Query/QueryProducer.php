<?php

namespace Gestione\Cms\Content\Page\Query;

use Gestione\Component\DBAL\DatabaseInterface;
use Gestione\Component\HttpFoundation\Request;

class QueryProducer implements QueryProducerInterface
{
    protected $database;
    protected $request;

    public function __construct(DatabaseInterface $database, Request $request)
    {
        $this->database = $database;
        $this->request  = $request;
    }

    public function produce(): QueryInterface
    {
        return new Query($this->database, $this->request->getLocale());
    }
}
