<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Contract;

/**
 * Interface CallTaskSourceInterface
 * @package GepurIt\ErpTaskBundle\CallTaskSource
 */
interface TaskProducerInterface
{
    /**
     * @return ErpTaskInterface|null
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
