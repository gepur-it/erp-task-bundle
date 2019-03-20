<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */

namespace GepurIt\ErpTaskBundle\Tests\Stubs;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProviderInterface;
use GepurIt\User\Security\User;

/**
 * Class ConcreteTestProvider
 * @package GepurIt\ErpTaskBundle\Tests\Stubs
 */
class ConcreteTestProvider implements TaskProviderInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'test';
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
     * @return array
     */
    public function findAllOpenedTasks(): array
    {
        // TODO: Implement findAllOpenedTasks() method.
        return [];
    }

    /**
     * @param User $user
     * @return array
     */
    public function findOpenedByUser(User $user): array
    {
        // TODO: Implement findOpenedByUser() method.
        return [];
    }

    /**
     * @param User $user
     * @return array
     */
    public function findLockedByUser(User $user): array
    {
        // TODO: Implement findLockedByUser() method.
        return [];
    }

    /**
     * @return array
     */
    public function findAllInProcess(): array
    {
        // TODO: Implement findAllInProcess() method.
        return [];
    }

    /**
     * @param string $clientId
     * @return array
     */
    public function findOpenedByClientId(string $clientId): array
    {
        // TODO: Implement findOpenedByClientId() method.
        return [];
    }

    public function getProducer(string $name): TaskProducerInterface
    {
        // TODO: Implement getSource() method.
    }

    public function getSources(): array
    {
        // TODO: Implement getSources() method.
    }
}
