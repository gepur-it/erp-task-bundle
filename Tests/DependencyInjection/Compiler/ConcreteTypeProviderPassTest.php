<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 27.08.18
 * Time: 10:53
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\DependencyInjection\Compiler;


use GepurIt\ErpTaskBundle\DependencyInjection\Compiler\ConcreteTypeProviderPass;
use GepurIt\ErpTaskBundle\Tests\Stubs\ConcreteTestProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class ConcreteTypeProviderPassTest
 * @package GepurIt\ErpTaskBundle\Tests\DependencyInjection\Compiler
 */
class ConcreteTypeProviderPassTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testProcessNotProvider()
    {
        /**@var ContainerBuilder|MockObject $containerBuilder */
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $concreteTypeProviderPass = new ConcreteTypeProviderPass();

        $containerBuilder->expects(
            $this->once()
        )
            ->method('has')
            ->willReturn(false);

        $concreteTypeProviderPass->process($containerBuilder);
    }

    /**
     * @throws \Exception
     */
    public function testProcess()
    {
        /**@var ContainerBuilder|MockObject $containerBuilder */
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        /**@var Definition|MockObject $definition */
        $definition = $this->createMock(Definition::class);
        /**@var ConcreteTestProvider|MockObject $concreteTestProvider */
        $concreteTestProvider = $this->createMock(ConcreteTestProvider::class);

        $concreteTypeProviderPass = new ConcreteTypeProviderPass();

        $containerBuilder->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $containerBuilder->expects($this->once())
            ->method('findDefinition')
            ->willReturn($definition);

        $containerBuilder->expects($this->once())
            ->method('findTaggedServiceIds')
            ->willReturn(['test']);

        $containerBuilder->expects($this->once())
            ->method('getDefinition')
            ->willReturn($concreteTestProvider);

        $definition->expects($this->once())
            ->method('addMethodCall')
            ->with('registerProvider', [$concreteTestProvider]);

        $concreteTypeProviderPass->process($containerBuilder);
    }
}
