<?php

namespace Gestione\Cms\Content\Page;

use Gestione\Cms\Content\Page\Query\QueryInterface;
use Gestione\Cms\Content\Page\Query\QueryProducerInterface;
use Gestione\Cms\Content\Page\Collection\CollectionInterface;

class PageManager
{
    protected $queryProducer;

    public function __construct(QueryProducerInterface $queryProducer)
    {
        $this->queryProducer = $queryProducer;
    }

    public function query(array $query): CollectionInterface
    {
        return $this->queryProducer->produce()->query($query);
    }

    public function queryRaw(array $query): CollectionInterface
    {
        return $this->queryProducer->produce()->queryRaw($query);
    }

    public function find($id): ?PageInterface
    {
        return $this->queryProducer->produce()->find($id)->first();
    }

    public function findBySlug($slug): ?PageInterface
    {
        return $this->queryProducer->produce()->findBySlug($slug)->first();
    }

    public function getQuery(): QueryInterface
    {
        return $this->queryProducer->produce();
    }

    public function setQueryProducer(QueryProducerInterface $queryProducer)
    {
        $this->queryProducer = $queryProducer;

        return $this;
    }

    public function getQueryProducer(): QueryProducerInterface
    {
        return $this->queryProducer;
    }
}
