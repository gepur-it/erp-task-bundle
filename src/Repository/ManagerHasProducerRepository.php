<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GepurIt\CallTaskBundle\Entity\ManagerHasTaskProducer;

/**
 * Class SourceToUserRelationRepository
 * @package GepurIt\Repository
 * @method ManagerHasTaskProducer[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ManagerHasTaskProducer|null find($id, $lockMode = null, $lockVersion = null)
 * @codeCoverageIgnore
 */
class ManagerHasCTRRepository extends EntityRepository
{
    /**
     * @param string $userId
     *
     * @return ManagerHasTaskProducer[]
     */
    public function findByUser(string $userId): array
    {
        return $this->findBy(['userId' => $userId], ['priority' => 'ASC']);
    }
}
