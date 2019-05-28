<?php

namespace Gestione\Cms\Content\Page\Setup;

use Gestione\Component\DBAL\Connection;
use Gestione\Component\Hooking\HookerInterface;
use Gestione\Cms\Content\Metadata\Syncer\SyncerInterface;
use Gestione\Cms\Content\Page\PageInterface;
use Gestione\Cms\Content\Page\Metadata\Metadata;

class SetupMetadata implements SetupperInterface
{
    protected $syncer;

    public function __construct(SyncerInterface $syncer)
    {
        $this->syncer = $syncer;
    }

    public function setup(PageInterface $page)
    {
        $page->setMetadata(new Metadata($page->getId(), $this->syncer));
    }
}
