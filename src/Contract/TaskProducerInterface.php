<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\CallTaskSource;

use GepurIt\CallTaskBundle\Contract\ErpTaskInterface;

/**
 * Interface CallTaskSourceInterface
 * @package GepurIt\CallTaskBundle\CallTaskSource
 */
interface TaskProducerInterface
{
    /**
     * @return \GepurIt\CallTaskBundle\Contract\ErpTaskInterface|null
     */
    public function getNext(): ?ErpTaskInterface;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
