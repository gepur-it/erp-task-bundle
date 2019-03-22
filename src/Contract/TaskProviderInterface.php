<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */

namespace GepurIt\ErpTaskBundle\Contract;

use GepurIt\ErpTaskBundle\ActionProcessor\ActionProcessorInterface;

/**
 * Class TaskProviderInterface
 * @package GepurIt\ErpTaskBundle\CallTaskSource
 */
interface TaskProviderInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $taskId
     *
     * @return ErpTaskInterface|null
     */
    public function findTask(string $taskId): ?ErpTaskInterface;

    /**
     * @param callable|null $filter
     *
     * @return TaskProducerInterface[]|iterable
     */
    public function getProducers(?callable $filter = null): iterable;

    /**
     * @param string $name
     *
     * @return TaskProducerInterface
     */
    public function getProducer(string $name): TaskProducerInterface;

    /**
     * @return ActionProcessorInterface
     */
    public function getActionProcessor(): ActionProcessorInterface;
}
