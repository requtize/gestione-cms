<?php

namespace Gestione\Cms;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Gestione\Component\HttpKernel\Module\Module;
use Gestione\Cms\DependencyInjection\SlugMatcherPass;
use Gestione\Cms\DependencyInjection\ContentSetupperPass;
use Gestione\Cms\DependencyInjection\MetadataRegistratorPass;

class CmsModule extends Module
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SlugMatcherPass);
        $container->addCompilerPass(new ContentSetupperPass);
        $container->addCompilerPass(new MetadataRegistratorPass);
    }
}
