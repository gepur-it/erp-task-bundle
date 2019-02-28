<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */

namespace GepurIt\CallTaskBundle\Tests\Stubs;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\CallTaskBundle\CallTaskSource\ConcreteCallTaskTypeProviderInterface;
use GepurIt\User\Security\User;

/**
 * Class ConcreteTestProvider
 * @package GepurIt\CallTaskBundle\Tests\Stubs
 */
class ConcreteTestProvider implements ConcreteCallTaskTypeProviderInterface
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
     * @return CallTaskInterface|null
     */
    public function find(string $taskId): ?CallTaskInterface
    {
        return new TestCallTask($taskId);
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
}
