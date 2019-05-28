<?php

namespace Gestione\Component\HttpKernel;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\DependencyInjection\AddAnnotatedClassesToCachePass;
use Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Gestione\Component\HttpKernel\Module\ModulesCollection;
use Gestione\Component\Config\FileLocator;
use Gestione\Component\Stdlib\Parameters;

class Kernel implements KernelInterface
{
    protected $booted = false;
    protected $startTime = 0;
    protected $parameters;
    protected $container;
    protected $environment;
    protected $debug;

    protected $modules;

    public function __construct(Parameters $parameters)
    {
        $this->parameters = $parameters;

        $this->environment = $parameters->get('environment', 'prod');
        $this->debug       = $parameters->get('debug', false);

        $this->modules = new ModulesCollection;
    }

    public function boot()
    {
        if($this->booted === true)
            return;

        $this->startTime = microtime(true);

        $this->initializeModules();
        $this->initializeContainer();

        foreach($this->getModules() as $module)
        {
            $module->setContainer($this->container);
            $module->boot();
        }

        $this->booted = true;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->boot();

        $this->container->set('request', $request);
        $this->container->get('request_stack')->push($request);

        return $this->getHttpKernel()->handle($request, $type, $catch);
    }

    public function terminate(Request $request, Response $response)
    {
        $this->getHttpKernel()->terminate($request, $response);
    }

    protected function getHttpKernel()
    {
        return $this->container->get('http_kernel');
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function getModules(): ModulesCollection
    {
        return $this->modules;
    }

    public function getProjectDir(): string
    {
        return $this->parameters->get('project-dir');
    }

    public function getConfigDir(): string
    {
        return $this->parameters->get('config-dir');
    }

    public function getCacheDir(): string
    {
        return $this->parameters->get('cache-dir');
    }

    protected function initializeModules()
    {
        $this->modules->empty();

        foreach($this->registerModules() as $module)
        {
            $name = $module->getName();

            if($this->modules->exists($name))
            {
                throw new \LogicException(sprintf('Trying to register two modules with the same name "%s"', $name));
            }

            $this->modules->add($module);
        }
    }

    protected function registerModules(): iterable
    {
        $modules = require $this->getConfigDir().'/modules.php';

        foreach($modules as $class)
        {
            yield new $class();
        }
    }

    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->container->set('kernel', $this);
        $this->container->set('modules_collection', $this->modules);

        $locator = new FileLocator;
        $loader  = new YamlFileLoader($this->container, $locator);

        foreach($this->getModules() as $module)
        {
            try
            {
                $locator->setPaths([ $module->getPath().'/Resources/config' ]);
                $loader->load('services.yaml');
            }
            catch(FileLocatorFileNotFoundException $e)
            {}
        }

        $this->container->compile();
    }

    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        $this->prepareContainer($container);

        /*if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
            $container->merge($cont);
        }*/

        //$container->addCompilerPass(new AddAnnotatedClassesToCachePass($this));

        return $container;
    }

    protected function getContainerBuilder()
    {
        $container = new ContainerBuilder;
        $container->getParameterBag()->add($this->getKernelParameters());

        return $container;
    }

    /**
     * Prepares the ContainerBuilder before it is compiled.
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
        $extensions = [];

        foreach($this->modules as $module)
        {
            if($extension = $module->getContainerExtension())
            {
                $container->registerExtension($extension);
            }
        }

        foreach($this->modules as $module)
        {
            $module->build($container);
        }

        foreach($container->getExtensions() as $extension)
        {
            $extensions[] = $extension->getAlias();
        }

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));
    }

    protected function getContainerLoader(ContainerInterface $container)
    {
        $locator = new FileLocator($this);
        $resolver = new LoaderResolver([
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new GlobFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ]);

        return new DelegatingLoader($resolver);
    }

    protected function getKernelParameters()
    {
        $modules = [];
        $modulesMetadata = [];

        foreach($this->modules as $name => $module)
        {
            $modules[$name] = get_class($module);

            $modulesMetadata[$name] = [
                'path' => $module->getPath(),
                'namespace' => $module->getNamespace(),
            ];
        }

        return [
            'kernel.project_dir' => $this->getProjectDir(),
            'kernel.config_dir' => $this->getConfigDir(),
            'kernel.cache_dir' => $this->getCacheDir(),
            'kernel.environment' => $this->environment,
            'kernel.debug' => $this->debug,
            //'kernel.cache_dir' => realpath($cacheDir = $this->warmupDir ?: $this->getCacheDir()) ?: $cacheDir,
            //'kernel.logs_dir' => realpath($this->getLogDir()) ?: $this->getLogDir(),
            'kernel.modules' => $modules,
            'kernel.modules_metadata' => $modulesMetadata,
            //'kernel.charset' => $this->getCharset(),
            'kernel.container_class' => $this->getContainerClass(),
        ];
    }

    protected function getContainerClass()
    {
        $class = \get_class($this);
        $class = 'c' === $class[0] && 0 === strpos($class, "class@anonymous\0") ? get_parent_class($class).str_replace('.', '_', ContainerBuilder::hash($class)) : $class;

        return 'Gestione_'.str_replace('\\', '_', $class).ucfirst($this->environment).($this->debug ? 'Debug' : '').'Container';
    }
}
