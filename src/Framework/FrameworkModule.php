<?php

namespace Gestione\Framework;

use Symfony\Component\Routing\DependencyInjection\RoutingResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\DependencyInjection\LoggerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterControllerArgumentLocatorsPass;
use Symfony\Component\HttpKernel\DependencyInjection\RemoveEmptyControllerArgumentLocatorsPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\ProfilerPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplatingPass;
use Symfony\Component\Validator\DependencyInjection\AddConstraintValidatorsPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\AddAnnotationsCachedReaderPass;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\Translation\DependencyInjection\TranslatorPass;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\LoggingTranslatorPass;
use Symfony\Component\Translation\DependencyInjection\TranslationExtractorPass;
use Symfony\Component\Translation\DependencyInjection\TranslationDumperPass;
use Symfony\Component\HttpKernel\DependencyInjection\ControllerArgumentValueResolverPass;
use Symfony\Component\Cache\DependencyInjection\CachePoolPass;
use Symfony\Component\Form\DependencyInjection\FormPass;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Gestione\Component\HttpKernel\Module\Module;
use Gestione\Component\Orm\DependencyInjection\RegisterEventListenersPass;

class FrameworkModule extends Module
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $hotPathEvents = [
            KernelEvents::REQUEST,
            KernelEvents::CONTROLLER,
            KernelEvents::CONTROLLER_ARGUMENTS,
            KernelEvents::RESPONSE,
            KernelEvents::FINISH_REQUEST,
        ];

        $container->addCompilerPass(new LoggerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -32);
        $container->addCompilerPass(new RegisterControllerArgumentLocatorsPass());
        //$container->addCompilerPass(new RemoveEmptyControllerArgumentLocatorsPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new RoutingResolverPass());
        //$container->addCompilerPass(new ProfilerPass());
        $container->addCompilerPass((new RegisterListenersPass())->setHotPathEvents($hotPathEvents), PassConfig::TYPE_BEFORE_REMOVING);
        //$container->addCompilerPass(new TemplatingPass());
        //$container->addCompilerPass(new AddConstraintValidatorsPass);
        //$container->addCompilerPass(new AddAnnotationsCachedReaderPass(), PassConfig::TYPE_AFTER_REMOVING, -255);
        //$container->addCompilerPass(new AddValidatorInitializersPass);
        //$container->addCompilerPass(new AddConsoleCommandPass, PassConfig::TYPE_BEFORE_REMOVING);
        //$container->addCompilerPass(new TranslatorPass);
        //$container->addCompilerPass(new LoggingTranslatorPass());
        //$container->addCompilerPass(new AddExpressionLanguageProvidersPass(false));
        //$container->addCompilerPass(new TranslationExtractorPass);
        //$container->addCompilerPass(new TranslationDumperPass);
        //$container->addCompilerPass(new FragmentRendererPass());
        //$container->addCompilerPass(new SerializerPass);
        //$container->addCompilerPass(new PropertyInfoPass);
        //$container->addCompilerPass(new DataCollectorTranslatorPass());
        $container->addCompilerPass(new ControllerArgumentValueResolverPass());
        //$container->addCompilerPass(new CachePoolPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 32);
        //$container->addCompilerPass(new ValidateWorkflowsPass);
        //$container->addCompilerPass(new CachePoolClearerPass(), PassConfig::TYPE_AFTER_REMOVING);
        //$container->addCompilerPass(new CachePoolPrunerPass(), PassConfig::TYPE_AFTER_REMOVING);
        //$container->addCompilerPass(new FormPass);
        //$container->addCompilerPass(new WorkflowGuardListenerPass());
        //$container->addCompilerPass(new ResettableServicePass());
        //$container->addCompilerPass(new TestServiceContainerWeakRefPass(), PassConfig::TYPE_BEFORE_REMOVING, -32);
        //$container->addCompilerPass(new TestServiceContainerRealRefPass(), PassConfig::TYPE_AFTER_REMOVING);
        //$container->addCompilerPass(new MessengerPass);

        /*if ($container->getParameter('kernel.debug')) {
            $container->addCompilerPass(new AddDebugLogProcessorPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -32);
            $container->addCompilerPass(new UnusedTagsPass(), PassConfig::TYPE_AFTER_REMOVING);
            $container->addCompilerPass(new ContainerBuilderDebugDumpPass(), PassConfig::TYPE_BEFORE_REMOVING, -255);
            $container->addCompilerPass(new CacheCollectorPass(), PassConfig::TYPE_BEFORE_REMOVING);
        }*/

        $container->addCompilerPass(new RegisterEventListenersPass);
    }
}
