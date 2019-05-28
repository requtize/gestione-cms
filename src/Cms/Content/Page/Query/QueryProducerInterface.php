<?php

namespace Gestione\Cms\Content\Page\Query;

interface QueryProducerInterface
{
    public function produce(): QueryInterface;
}
