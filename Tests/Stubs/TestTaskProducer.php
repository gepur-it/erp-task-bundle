<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:34
 */

namespace GepurIt\ErpTaskBundle\Tests\Stubs;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;
use Yawa20\RegistryBundle\Registrable\RegistrableInterface;

/**
 * Class TestSource
 * @package GepurIt\ErpTaskBundle\Tests\Stubs
 */
class TestTaskProducer implements TaskProducerInterface, RegistrableInterface
{
    /**
     * @return ErpTaskInterface|null
     */
    public function getNext(): ?ErpTaskInterface
    {
        return new TestErpTask('42');
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'test';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'test';
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return 'test';
    }
}
