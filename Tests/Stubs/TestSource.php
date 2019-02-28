<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:34
 */

namespace GepurIt\CallTaskBundle\Tests\Stubs;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\CallTaskBundle\CallTaskSource\SourceInterface;
use Yawa20\RegistryBundle\Registrable\RegistrableInterface;

/**
 * Class TestSource
 * @package GepurIt\CallTaskBundle\Tests\Stubs
 */
class TestSource implements SourceInterface, RegistrableInterface
{
    /**
     * @return CallTaskInterface|null
     */
    public function getNext(): ?CallTaskInterface
    {
        return new TestCallTask('42');
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
