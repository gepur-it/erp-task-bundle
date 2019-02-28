<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\CallTaskSource;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Interface CallTaskSourceInterface
 * @package GepurIt\CallTaskBundle\CallTaskSource
 */
interface SourceInterface
{
    /**
     * @return CallTaskInterface|null
     */
    public function getNext(): ?CallTaskInterface;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
