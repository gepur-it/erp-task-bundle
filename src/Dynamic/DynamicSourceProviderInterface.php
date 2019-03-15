<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 28.02.19
 */

namespace GepurIt\ErpTaskBundle\Dynamic;

/**
 * Class DynamicSourceProviderInterface
 * @package GepurIt\ErpTaskBundle\Dynamic
 */
interface DynamicSourceProviderInterface
{
    /**
     * @return DynamicTaskProducerInterface[]
     */
    public function getSources(): array;
}
