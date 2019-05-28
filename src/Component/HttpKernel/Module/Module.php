<?php

namespace Gestione\Component\HttpKernel\Module;

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

abstract class Module
{
    use ContainerAwareTrait;

    protected $name;
    protected $extension;
    protected $path;
    private $namespace;

    public function boot()
    {

    }

    public function shutdown()
    {

    }

    public function build(ContainerBuilder $container)
    {

    }

    public function getContainerExtension()
    {
        if($this->extension === null)
        {
            $extension = $this->createContainerExtension();

            if($extension !== null)
            {
                if(! $extension instanceof ExtensionInterface)
                {
                    throw new \LogicException(sprintf('Extension %s must implement Symfony\Component\DependencyInjection\Extension\ExtensionInterface.', \get_class($extension)));
                }

                $basename = preg_replace('/Bundle$/', '', $this->getName());
                $expectedAlias = Container::underscore($basename);

                if($extension->getAlias() != $expectedAlias)
                {
                    throw new \LogicException(sprintf('Users will expect the alias of the default extension of a bundle to be the underscored version of the bundle name ("%s"). You can override "Bundle::getContainerExtension()" if you want to use "%s" or another alias.', $expectedAlias, $extension->getAlias()));
                }

                $this->extension = $extension;
            }
            else
            {
                $this->extension = false;
            }
        }

        if($this->extension)
            return $this->extension;
    }

    public function getNamespace()
    {
        if($this->namespace === null)
            $this->parseClassName();

        return $this->namespace;
    }

    public function getPath()
    {
        if ($this->path === null)
        {
            $reflected  = new \ReflectionObject($this);
            $this->path = dirname($reflected->getFileName());
        }

        return $this->path;
    }

    final public function getName()
    {
        if ($this->name === null)
            $this->parseClassName();

        return $this->name;
    }

    public function registerCommands(Application $application)
    {

    }

    protected function getContainerExtensionClass()
    {
        $basename = preg_replace('/Module$/', '', $this->getName());

        return $this->getNamespace().'\\DependencyInjection\\'.$basename.'Extension';
    }

    protected function createContainerExtension()
    {
        if(class_exists($class = $this->getContainerExtensionClass()))
            return new $class();
    }

    private function parseClassName()
    {
        $position = strrpos(static::class, '\\');
        $this->namespace = $position === false ? '' : substr(static::class, 0, $position);

        if($this->name === null)
        {
            $this->name = $position === false ? static::class : substr(static::class, $position + 1);
        }
    }
}
