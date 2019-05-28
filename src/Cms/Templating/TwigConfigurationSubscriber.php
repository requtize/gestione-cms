<?php

namespace Gestione\Cms\Templating;

use Twig_Loader_Array;
use Twig_Loader_Filesystem;
use Gestione\Component\Hooking\Subscriber\AbstractSubscriber;
use Gestione\Component\Hooking\Configuration;

class TwigConfigurationSubscriber extends AbstractSubscriber
{
    public function configure(Configuration $configuration)
    {
        $configuration->registerAction('templating.twig.loader.filesystem', 'configureFilesystemLoader');
        $configuration->registerAction('templating.twig.loader.array', 'configureArrayLoader');
    }

    public function configureFilesystemLoader(Twig_Loader_Filesystem $loader)
    {
        $loader->addPath(__DIR__.'/../Http/View', 'cms');

        return $loader;
    }

    public function configureArrayLoader(Twig_Loader_Array $loader)
    {
        $loader->setTemplate('theme', "{% extends '@cms/index.tpl' %}");

        return $loader;
    }
}
