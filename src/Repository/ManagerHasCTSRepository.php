<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GepurIt\CallTaskBundle\Entity\ManagerHasCTS;

/**
 * Class SourceToUserRelationRepository
 * @package GepurIt\Repository
 * @method ManagerHasCTS[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ManagerHasCTS|null find($id, $lockMode = null, $lockVersion = null)
 * @codeCoverageIgnore
 */
class ManagerHasCTSRepository extends EntityRepository
{
    /**
     * @param string $userId
     *
     * @return ManagerHasCTS[]
     */
    public function findByUser(string $userId): array
    {
        return $this->findBy(['userId' => $userId], ['priority' => 'ASC']);
    }
}
