<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */

namespace GepurIt\CallTaskBundle\DependencyInjection\Compiler;

use GepurIt\CallTaskBundle\CallTaskSource\CallTaskProvider;
use GepurIt\CallTaskBundle\DependencyInjection\CallTaskExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ConcreteTypeProviderPass
 * @package GepurIt\CallTaskBundle\DependencyInjection\Compiler
 */
class ConcreteTypeProviderPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CallTaskProvider::class)) {
            return;
        }

        $provider       = $container->findDefinition(CallTaskProvider::class);
        $taggedServices = $container->findTaggedServiceIds(CallTaskExtension::CONCRETE_PROVIDER_TAG);

        foreach (array_keys($taggedServices) as $key) {
            $concreteProvider = $container->getDefinition($key);
            $provider->addMethodCall('registerProvider', [$concreteProvider]);
        }
    }
}
