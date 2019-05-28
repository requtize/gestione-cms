<?php

namespace Gestione\Cms\Content\Page\Query;

use Gestione\Cms\Content\Page\Collection\CollectionInterface;

interface QueryInterface
{
    public function query(array $query): CollectionInterface;
}
