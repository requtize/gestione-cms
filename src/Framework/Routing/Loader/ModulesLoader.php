<?php

namespace Gestione\Framework\Routing\Loader;

use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Gestione\Component\Config\FileLocator;
use Gestione\Component\HttpFoundation\Request;
use Gestione\Component\HttpKernel\Module\ModulesCollection;
use Gestione\Component\I18n\Locales;

class ModulesLoader extends BaseLoader
{
    protected $locales;
    protected $request;

    public function __construct(Locales $locales, Request $request)
    {
        $this->locales = $locales;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $locator = new FileLocator;
        $loader  = new YamlFileLoader($locator);
        $collectionFront = new RouteCollection;
        $collectionAdmin = new RouteCollection;

        foreach($resource as $module)
        {
            $locator->setPaths([ $module->getPath().'/Resources/config' ]);

            try
            {
                $routes = $loader->load('routing-front.yaml');

                $collectionFront->addCollection($routes);
                foreach($routes->getResources() as $resource)
                    $collectionFront->addResource($resource);
            }
            catch(FileLocatorFileNotFoundException $e)
            {}

            try
            {
                $routes = $loader->load('routing-admin.yaml');

                $collectionAdmin->addCollection($routes);
                foreach($routes->getResources() as $resource)
                    $collectionAdmin->addResource($resource);
            }
            catch(FileLocatorFileNotFoundException $e)
            {}
        }

        // Allowed empty locale segment in URI.
        $locales = [ '' ];

        foreach($this->locales as $locale)
        {
            // Skip default locale. Default locale is an empty URI segment.
            if($this->request->getDefaultLocale() == $locale)
                continue;

            $locales[] = $locale->getSlug();
        }

        $locales = implode('|', $locales);

        $collectionFront->addPrefix('{_locale}');
        $collectionFront->addDefaults([ '_backend' => false, '_locale' => 'pl' ]);
        $collectionFront->addRequirements([ '_locale' => $locales ]);

        $collectionAdmin->addNamePrefix('admin.');
        $collectionAdmin->addPrefix('backend/{_locale}');
        $collectionAdmin->addDefaults([ '_backend' => true, '_locale' => 'pl' ]);
        $collectionAdmin->addRequirements([ '_locale' => $locales ]);

        $collection = new RouteCollection;
        $collection->addCollection($collectionFront);
        $collection->addCollection($collectionAdmin);

        foreach($collectionAdmin->getResources() as $resource)
            $collection->addResource($resource);
        foreach($collectionFront->getResources() as $resource)
            $collection->addResource($resource);

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $resource instanceof ModulesCollection;
    }
}
