<?php

namespace Gestione\Cms\Routing;

use Gestione\Cms\Routing\MatchInterface;

interface SlugMatcherInterface
{
    public function match(string $slug): MatchInterface;
    public function supportsPagination(): bool;
}
