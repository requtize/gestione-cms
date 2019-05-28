<?php

namespace Gestione\Cms\Content\Page\Metadata;

use Gestione\Cms\Content\Metadata\Registrator\RegistratorInterface;
use Gestione\Cms\Content\Metadata\Registrator\RegistryInterface;

class Registrator implements RegistratorInterface
{
    public function register(RegistryInterface $registry)
    {
        $fields = $registry->getContentFields('page');
        $fields->add([
            'name'  => 'thumbnail',
            'label' => 'Miniaturka'
        ]);
    }
}
