<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\ErpTaskBundle\TaskMarker;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Entity\TaskMark;
use GepurIt\ErpTaskBundle\Exception\TaskMarkNotFoundException;
use GepurIt\ErpTaskBundle\Repository\ErpTaskMarkRepository;

/**
 * Class TaskMarker
 * @package GepurIt\ErpTaskBundle\TaskMarker
 */
class TaskMarker implements TaskMarkerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaskMark(string $userId): ?TaskMarkInterface
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(TaskMark::class);

        return $repository->finOneByUserId($userId);
    }

    /**
     * {@inheritdoc}
     */
    public function markTask(ErpTaskInterface $callTask, string $userId): TaskMarkInterface
    {
        $mark = new TaskMark(
            $callTask->getTaskId(),
            $callTask->getType(),
            $userId,
            $callTask->getGroupId()
        );

        $this->entityManager->persist($mark);
        $callTask->setLockedBy($userId);

        return $mark;
    }

    /**
     * @param ErpTaskInterface $callTask
     * @param bool $unlock
     */
    public function unmarkTask(ErpTaskInterface $callTask, bool $unlock = true): void
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(TaskMark::class);
        $mark = $repository->findOneByTask($callTask);

        if (null === $mark) {
            return;
        }

        if ($unlock) {
            $callTask->setLockedBy(null);
        }

        $this->entityManager->remove($mark);
    }

    /**
     * @param ErpTaskInterface $callTask
     *
     * @return TaskMarkInterface|null
     */
    public function getMarkByTask(ErpTaskInterface $callTask): ?TaskMarkInterface
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(TaskMark::class);

        return $repository->findOneByTask($callTask);
    }

    /**
     * @param ErpTaskInterface $callTask
     * @param string $userId
     */
    public function transferTaskMark(ErpTaskInterface $callTask, string $userId): void
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(TaskMark::class);
        $mark = $repository->findOneByTask($callTask);

        if (null === $mark) {
            throw new TaskMarkNotFoundException($callTask);
        }

        $callTask->setLockedBy($userId);

        $mark->transferTo($userId);
    }

    /**
     * @param string $groupKey
     *
     * @return TaskMarkInterface[]|\Generator
     */
    public function getMarksByGroup(string $groupKey): iterable
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(TaskMark::class);

        yield from $repository->findBy(['groupKey' => $groupKey]);
    }

    /**
     * @param string $userId
     * @param string $groupKey
     * @return TaskMarkInterface[]|\Generator
     */
    public function getMarksByGroupAndUserId(string $userId, string $groupKey): iterable
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(TaskMark::class);

        yield from $repository->findBy(['userId' => $userId, 'groupKey' => $groupKey]);
    }
}
