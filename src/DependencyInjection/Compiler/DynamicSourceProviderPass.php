<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 28.02.19
 */

namespace GepurIt\ErpTaskBundle\DependencyInjection\Compiler;

use GepurIt\ErpTaskBundle\Dynamic\DynamicSourceProviderRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DynamicSourceProviderPass
 * @package GepurIt\ErpTaskBundle\DependencyInjection\Compiler
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
