<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:34
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\Stubs;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;

/**
 * Class TestSource
 * @package GepurIt\ErpTaskBundle\Tests\Stubs
 */
class TestTaskProducer implements TaskProducerInterface
{
    /**
     * @return ErpTaskInterface|null
     */
    public function getNext(): ?ErpTaskInterface
    {
        return new TestErpTask('test42');
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
}
