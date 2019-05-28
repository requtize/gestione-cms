<?php

namespace Gestione\Cms\Content\Page\Collection;

use ArrayIterator;
use IteratorAggregate;
use Gestione\Cms\Content\Page\PageInterface;

class Collection implements CollectionInterface, IteratorAggregate
{
    protected $pages = [];

    public function __construct(array $pages = [])
    {
        foreach($pages as $page)
        {
            $this->append($page);
        }
    }

    public function append(PageInterface $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    public function merge(CollectionInterface $collection)
    {
        foreach($collection as $page)
        {
            $this->append($page);
        }

        return $this;
    }

    public function count(): int
    {
        return count($this->pages);
    }

    public function first(): ?PageInterface
    {
        return isset($this->pages[0]) ? $this->pages[0] : null;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->parameters);
    }
}
