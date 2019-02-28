<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */

namespace GepurIt\CallTaskBundle\CallTaskSource;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\User\Security\User;

/**
 * Class ConcreteCallTaskTypeProviderInterface
 * @package GepurIt\CallTaskBundle\CallTaskSource
 */
interface ConcreteCallTaskTypeProviderInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $taskId
     *
     * @return CallTaskInterface|null
     */
    public function find(string $taskId): ?CallTaskInterface;

    /**
     * @return array
     */
    public function findAllOpenedTasks(): array;

    /**
     * @param User $user
     *
     * @return array
     */
    public function findOpenedByUser(User $user): array;

    /**
     * @param User $user
     *
     * @return array
     */
    public function findLockedByUser(User $user): array;

    /**
     * @return array
     */
    public function findAllInProcess(): array;
}
