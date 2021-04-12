<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\Stubs;

use GepurIt\ErpTaskBundle\ActionProcessor\ActionProcessorInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProviderInterface;

/**
 * Class ConcreteTestNullProvider
 * @package GepurIt\ErpTaskBundle\Tests\Stubs
 */
class ConcreteTestNullProvider implements TaskProviderInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'testNull';
    }

    /**
     * @param string $taskId
     *
     * @return ErpTaskInterface|null
     */
    public function findTask(string $taskId): ?ErpTaskInterface
    {
        return new TestErpTask($taskId);
    }

    /**
     * @param callable|null $filter
     *
     * @return TaskProducerInterface[]|iterable
     */
    public function getProducers(?callable $filter = null): iterable
    {
        return [
            'testNull' => $this->getProducer('testNull')
        ];
    }

    /**
     * @param string $name
     *
     * @return TaskProducerInterface
     */
    public function getProducer(string $name): TaskProducerInterface
    {
        return new TestProducerNullNext();
    }

    /**
     * @return ActionProcessorInterface
     */
    public function getActionProcessor(): ActionProcessorInterface
    {

    }

    /**
     * @return ErpTaskInterface[]
     */
    public function listOpenedTasks($limit = -1, $offset = 0, ?callable $filter = null): iterable
    {
        yield from [];
    }

    /**
     * @return ErpTaskInterface[]|\Generator|iterable
     */
    public function listAllTasks($limit = -1, $offset = 0, ?callable $filter = null): iterable
    {
        yield from [];
    }
}
