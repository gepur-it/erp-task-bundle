<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarkInterface;

/**
 * Class CallTaskMarkRepository
 * @package GepurIt\ErpTaskBundle\Repository
 * @method TaskMarkInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskMarkInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskMarkInterface[] findAll()
 * @method TaskMarkInterface[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @codeCoverageIgnore
 */
class ErpTaskMarkRepository extends EntityRepository
{
    /**
     * @param string $userId
     *
     * @return null|TaskMarkInterface
     */
    public function finOneByUserId(string $userId): ?TaskMarkInterface
    {
        $queryBuilder = $this->createQueryBuilder('callTaskMark')
            ->where('callTaskMark.userId = :userId')->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->orderBy('callTaskMark.createdAt', 'ASC');

        /** @var TaskMarkInterface[] $result */
        $result = $queryBuilder->getQuery()->execute();

        if (!empty($result)) {
            return reset($result);
        }

        return null;
    }

    /**
     * @param string $userId
     * @param string $taskType
     *
     * @return TaskMarkInterface|null
     */
    public function finOneByUserIdAndTaskType(string $userId, string $taskType): ?TaskMarkInterface
    {
        $queryBuilder = $this->createQueryBuilder('callTaskMark')
            ->where('callTaskMark.userId = :userId')->setParameter('userId', $userId)
            ->andWhere('callTaskMark.taskType = :taskType')->setParameter('taskType', $taskType)
            ->setMaxResults(1)
            ->orderBy('callTaskMark.createdAt', 'ASC');

        /** @var TaskMarkInterface[] $result */
        $result = $queryBuilder->getQuery()->execute();

        if (!empty($result)) {
            return reset($result);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getGroupKeys(): array
    {
        $queryBuilder = $this->createQueryBuilder('ctm')
            ->select("ctm.groupKey");

        $clients = $queryBuilder->getQuery()->getScalarResult();

        $clientsIds = array_column($clients, 'groupKey');

        return $clientsIds;
    }

    /**
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     *
     * @return TaskMarkInterface|null
     */
    public function findOneByTaskRelatively(ErpTaskInterface $callTask): ?TaskMarkInterface
    {
        $query = $this->createQueryBuilder('callTaskMark')
            ->where('callTaskMark.taskId = :taskId')
            ->setParameter('taskId', $callTask->getTaskId())
            ->orWhere('callTaskMark.groupKey = :groupKey')->setParameter('groupKey', $callTask->getGroupId())
            ->setMaxResults(1)
            ->getQuery();

        try {
            $mark = $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            return null;
        }

        return $mark;
    }

    /**
     * @param ErpTaskInterface $callTask
     *
     * @return TaskMarkInterface|null
     */
    public function findOneByTask(ErpTaskInterface $callTask)
    {
        return $this->find(['taskId' => $callTask->getTaskId(), 'taskType' => $callTask->getType()]);
    }
}
