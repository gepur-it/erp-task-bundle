<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:51
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\Stubs;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Class TestSourceNullNext
 * @package GepurIt\ErpTaskBundle\Tests\Stubs
 */
class TestProducerNullNext extends TestTaskProducer
{
    /**
     * @return ErpTaskInterface|null
     */
    public function getNext(): ?ErpTaskInterface
    {
        return null;
    }
}
