<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\DependencyInjection\Compiler;

use GepurIt\ErpTaskBundle\DependencyInjection\ErpTaskExtension;
use GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ConcreteTypeProviderPass
 * @package GepurIt\ErpTaskBundle\DependencyInjection\Compiler
 */
class ConcreteTypeProviderPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(BaseTaskProvider::class)) {
            return;
        }

        $provider       = $container->findDefinition(BaseTaskProvider::class);
        $taggedServices = $container->findTaggedServiceIds(ErpTaskExtension::CONCRETE_PROVIDER_TAG);

        foreach (array_keys($taggedServices) as $key) {
            $concreteProvider = $container->getDefinition((string)$key);
            $provider->addMethodCall('registerProvider', [$concreteProvider]);
        }
    }
}
