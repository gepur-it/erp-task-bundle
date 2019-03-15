<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 25.07.18
 */

namespace GepurIt\CallTaskBundle\CallTaskSource;

use GepurIt\CallTaskBundle\Contract\ErpTaskInterface;
use GepurIt\User\Security\User;

/**
 * Class TaskProviderInterface
 * @package GepurIt\CallTaskBundle\CallTaskSource
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
     * @return \GepurIt\CallTaskBundle\Contract\ErpTaskInterface|null
     */
    public function find(string $taskId): ?ErpTaskInterface;

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

    /**
     * @return TaskProducerInterface[]
     */
    public function getSources(): array;

    /**
     * @param string $name
     *
     * @return TaskProducerInterface
     */
    public function getSource(string $name): TaskProducerInterface;
}
