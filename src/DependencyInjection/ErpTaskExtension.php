<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\DependencyInjection;

use GepurIt\ErpTaskBundle\Contract\TaskProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class CallTaskExtension
 * @package GepurIt\ErpTaskBundle\DependencyInjection
 * @codeCoverageIgnore
 */
class ErpTaskExtension extends Extension
{
    const CONCRETE_PROVIDER_TAG = 'call_task.concrete_provider';

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(TaskProviderInterface::class)
            ->addTag(self::CONCRETE_PROVIDER_TAG);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
