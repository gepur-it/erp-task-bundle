<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\ErpTaskBundle\CurrentTaskMarker;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Entity\CallTaskMark;
use GepurIt\ErpTaskBundle\Exception\TaskMarkNotFoundException;
use GepurIt\ErpTaskBundle\Repository\ErpTaskMarkRepository;

/**
 * Class CurrentTaskSource
 * @package GepurIt\ErpTaskBundle\CallTask
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
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);

        return $repository->finOneByUserId($userId);
    }

    /**
     * {@inheritdoc}
     */
    public function markTask(ErpTaskInterface $callTask, string $userId): CurrentTaskMarkInterface
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
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     * @param bool                                              $unlock
     */
    public function unmarkTask(ErpTaskInterface $callTask, bool $unlock = true): void
    {
        /** @var ErpTaskMarkRepository $repository */
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
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     *
     * @return CallTaskMark|null
     */
    public function getMarkByTask(ErpTaskInterface $callTask): ?CurrentTaskMarkInterface
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);

        return $repository->findOneByTaskRelatively($callTask);
    }

    /**
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     * @param string                                            $userId
     */
    public function transferTaskMark(ErpTaskInterface $callTask, string $userId): void
    {
        /** @var ErpTaskMarkRepository $repository */
        $repository = $this->entityManager->getRepository(CallTaskMark::class);
        $mark       = $repository->findOne($callTask);

        if (null === $mark) {
            throw new TaskMarkNotFoundException($callTask);
        }

        $callTask->setLockedBy($userId);

        $mark->transferTo($userId);
    }
}
