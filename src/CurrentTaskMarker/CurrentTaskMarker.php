<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\CallTaskBundle\CurrentTaskMarker;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\CallTaskBundle\Entity\CallTaskMark;
use GepurIt\CallTaskBundle\Exception\TaskMarkNotFoundException;
use GepurIt\CallTaskBundle\Repository\CallTaskMarkRepository;

/**
 * Class CurrentTaskSource
 * @package GepurIt\CallTaskBundle\CallTask
 */
class CurrentTaskMarker implements CurrentTaskMarkerInterface
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
    public function getTaskMark(string $userId): ?CurrentTaskMarkInterface
    {
        /** @var CallTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);

        return $repository->finOneByUserId($userId);
    }

    /**
     * {@inheritdoc}
     */
    public function markTask(CallTaskInterface $callTask, string $userId): CurrentTaskMarkInterface
    {
        $mark = new CallTaskMark(
            $callTask->getTaskId(),
            $callTask->getType(),
            $userId,
            $callTask->getClientId()
        );

        $this->entityManager->persist($mark);
        $callTask->setLockedBy($userId);

        return $mark;
    }

    /**
     * @param CallTaskInterface $callTask
     * @param bool              $unlock
     */
    public function unmarkTask(CallTaskInterface $callTask, bool $unlock = true): void
    {
        /** @var CallTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);
        $mark       = $repository->findOne($callTask);

        if (null === $mark) {
            return;
        }

        if ($unlock) {
            $callTask->setLockedBy(null);
        }

        $this->entityManager->remove($mark);
    }

    /**
     * @param CallTaskInterface $callTask
     *
     * @return CallTaskMark|null
     */
    public function getMarkByTask(CallTaskInterface $callTask): ?CurrentTaskMarkInterface
    {
        /** @var CallTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);

        return $repository->findOneByTaskRelatively($callTask);
    }

    /**
     * @param CallTaskInterface $callTask
     * @param string            $userId
     */
    public function transferTaskMark(CallTaskInterface $callTask, string $userId): void
    {
        /** @var CallTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);
        $mark       = $repository->findOne($callTask);

        if (null === $mark) {
            throw new TaskMarkNotFoundException($callTask);
        }

        $callTask->setLockedBy($userId);

        $mark->transferTo($userId);
    }
}
