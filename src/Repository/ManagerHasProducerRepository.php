<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GepurIt\ErpTaskBundle\Entity\ManagerHasTaskProducer;

/**
 * Class SourceToUserRelationRepository
 * @package GepurIt\Repository
 * @method ManagerHasTaskProducer[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ManagerHasTaskProducer|null find($id, $lockMode = null, $lockVersion = null)
 * @codeCoverageIgnore
 */
class ManagerHasProducerRepository extends EntityRepository
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
