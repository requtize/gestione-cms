<?php

namespace Gestione\Cms\Content\Page\Metadata;

use Gestione\Cms\Content\Metadata\Storage\AbstractDatabaseStorage;

class Storage extends AbstractDatabaseStorage
{
    public function getContentType(): string
    {
        return 'page';
    }
}
