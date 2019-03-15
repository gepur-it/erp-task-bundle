<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 11.05.18
 */

namespace GepurIt\CallTaskBundle;

use GepurIt\CallTaskBundle\DependencyInjection\Compiler\ConcreteTypeProviderPass;
use GepurIt\CallTaskBundle\DependencyInjection\Compiler\DynamicSourceProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CallTaskBundle
 * @package GepurIt\CallTaskBundle
 * @codeCoverageIgnore
 */
class CallTaskBundle extends Bundle
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
