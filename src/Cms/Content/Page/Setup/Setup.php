<?php

namespace Gestione\Cms\Content\Page\Setup;

use Gestione\Cms\Content\Page\PageInterface;
use Gestione\Cms\Setup\SetupInterface;
use Gestione\Cms\Setup\AbstractSetup;

class Setup extends AbstractSetup
{
    public function addSetupper(SetupperInterface $setupper): SetupInterface
    {
        $this->setuppers[] = $setupper;

        return $this;
    }

    public function setup(PageInterface $page)
    {
        $this->callSetuppers($page);
    }
}
