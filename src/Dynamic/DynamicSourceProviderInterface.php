<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 28.02.19
 */

namespace GepurIt\CallTaskBundle\Dynamic;

/**
 * Class DynamicSourceProviderInterface
 * @package GepurIt\CallTaskBundle\Dynamic
 */
interface DynamicSourceProviderInterface
{
    /**
     * @return DynamicSourceInterface[]
     */
    public function getSources(): array;
}
