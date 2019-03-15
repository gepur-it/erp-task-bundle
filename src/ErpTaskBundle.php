<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 11.05.18
 */

namespace GepurIt\ErpTaskBundle;

use GepurIt\ErpTaskBundle\DependencyInjection\Compiler\ConcreteTypeProviderPass;
use GepurIt\ErpTaskBundle\DependencyInjection\Compiler\DynamicSourceProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ErpTaskBundle
 * @package GepurIt\ErpTaskBundle
 * @codeCoverageIgnore
 */
class ErpTaskBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConcreteTypeProviderPass());
        $container->addCompilerPass(new DynamicSourceProviderPass());
    }
}
