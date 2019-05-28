<?php

namespace Gestione\Cms\Content\Page\Setup;

use Gestione\Cms\Content\Page\PageInterface;

interface SetupperInterface
{
    public function setup(PageInterface $page);
}
