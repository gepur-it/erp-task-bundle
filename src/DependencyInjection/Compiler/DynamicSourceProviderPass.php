<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 28.02.19
 */

namespace GepurIt\CallTaskBundle\DependencyInjection\Compiler;

use GepurIt\CallTaskBundle\Dynamic\DynamicSourceProviderRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DynamicSourceProviderPass
 * @package GepurIt\CallTaskBundle\DependencyInjection\Compiler
 */
class DynamicSourceProviderPass implements CompilerPassInterface
{
    const SOURCE_PROVIDER_TAG = 'task.dynamic.source.provider';

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $sourceProviders = $container->findTaggedServiceIds(self::SOURCE_PROVIDER_TAG);
        $registry        = $container->findDefinition(DynamicSourceProviderRegistry::class);
        foreach (array_keys($sourceProviders) as $providerName) {
            $sourceProvider = $container->getDefinition($providerName);
            $registry->addMethodCall('add', [$sourceProvider]);
        }
    }
}
