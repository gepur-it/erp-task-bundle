<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\CallTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use GepurIt\CallTaskBundle\Contract\ErpTaskInterface;
use GepurIt\CallTaskBundle\Entity\CallTaskMark;

/**
 * Class CallTaskMarkRepository
 * @package GepurIt\CallTaskBundle\Repository
 * @method CallTaskMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method CallTaskMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method CallTaskMark[] findAll()
 * @method CallTaskMark[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @codeCoverageIgnore
 */
class CallTaskMarkRepository extends EntityRepository
{
    /**
     * @param string $userId
     *
     * @return null|CallTaskMark
     */
    public function finOneByUserId(string $userId): ?CallTaskMark
    {
        $queryBuilder = $this->createQueryBuilder('callTaskMark')
            ->where('callTaskMark.userId = :userId')->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->orderBy('callTaskMark.createdAt', 'ASC');

        /** @var CallTaskMark[] $result */
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
     * @return CallTaskMark|null
     */
    public function finOneByUserIdAndTaskType(string $userId, string $taskType): ?CallTaskMark
    {
        $queryBuilder = $this->createQueryBuilder('callTaskMark')
            ->where('callTaskMark.userId = :userId')->setParameter('userId', $userId)
            ->andWhere('callTaskMark.taskType = :taskType')->setParameter('taskType', $taskType)
            ->setMaxResults(1)
            ->orderBy('callTaskMark.createdAt', 'ASC');

        /** @var CallTaskMark[] $result */
        $result = $queryBuilder->getQuery()->execute();

        if (!empty($result)) {
            return reset($result);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getLockedClintIds(): array
    {
        $queryBuilder = $this->createQueryBuilder('ctm')
            ->select("ctm.clientId");

        $clients = $queryBuilder->getQuery()->getScalarResult();

        $clientsIds = array_column($clients, 'clientId');

        return $clientsIds;
    }

    /**
     * @param \GepurIt\CallTaskBundle\Contract\ErpTaskInterface $callTask
     *
     * @return CallTaskMark|null
     */
    public function findOneByTaskRelatively(ErpTaskInterface $callTask): ?CallTaskMark
    {
        $query = $this->createQueryBuilder('callTaskMark')
            ->where('callTaskMark.taskId = :taskId')
            ->setParameter('taskId', $callTask->getTaskId())
            ->orWhere('callTaskMark.clientId = :clientId')->setParameter('clientId', $callTask->getClientId())
            ->setMaxResults(1)
            ->getQuery();

        try {
            $mark = $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            return null;
        }

        return $mark;
    }

    public function findOne(ErpTaskInterface $callTask)
    {
        return $this->find(['taskId' => $callTask->getTaskId(), 'taskType' => $callTask->getType()]);
    }
}
