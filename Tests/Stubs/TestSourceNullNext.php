<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:51
 */

namespace GepurIt\CallTaskBundle\Tests\Stubs;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Class TestSourceNullNext
 * @package GepurIt\CallTaskBundle\Tests\Stubs
 */
class TestSourceNullNext extends TestSource
{
    /**
     * @return CallTaskInterface|null
     */
    public function getNext(): ?CallTaskInterface
    {
        return null;
    }
}
