<?php

namespace Gestione\Component\Templating\Twig;

use Twig_Loader_Chain;
use Twig_Loader_Array;
use Twig_Loader_Filesystem;
use Twig_Profiler_Profile;
use Twig_Extension_Profiler;
use Twig\Environment;
use Gestione\Component\Hooking\HookerInterface;

class EnvironmentFactory
{
    public static function createTwig($cacheDir, $debug, HookerInterface $hooker)
    {
        $filesystem = new Twig_Loader_Filesystem;
        
        $filesystem->addPath($cacheDir.'/../../src/Component/Profiler/Http/View', 'profiler');
        $filesystem = $hooker->doFilter('templating.twig.loader.filesystem', $filesystem);

        $array = $hooker->doFilter('templating.twig.loader.array', new Twig_Loader_Array([
            'theme' => '{% block content %}{% endblock %}'
        ]));

        $loaders = [ $array, $filesystem ];

        return new Environment(new Twig_Loader_Chain($loaders), [
            'cache' => $cacheDir.'/templating',
            'debug' => $debug,
            'auto_reload' => true,
            'strict_variables' => $debug
        ]);
    }
}
