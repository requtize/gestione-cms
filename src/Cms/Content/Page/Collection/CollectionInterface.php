<?php

namespace Gestione\Cms\Content\Page\Collection;

use Gestione\Cms\Content\Page\PageInterface;

interface CollectionInterface
{
    public function append(PageInterface $page);

    public function merge(CollectionInterface $collection);
    public function count(): int;
    public function first(): ?PageInterface;
}
