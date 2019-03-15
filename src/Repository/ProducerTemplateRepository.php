<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\CallTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use GepurIt\CallTaskBundle\Entity\SourceTemplate;

/**
 * Class SourceTemplateRepository
 * @package GepurIt\CallTaskBundle\Repository
 * @method SourceTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @codeCoverageIgnore
 */
class SourceTemplateRepository extends EntityRepository
{
    /**
     * @return SourceTemplate
     */
    public function getDefault(): SourceTemplate
    {
        $queryBuilder = $this->createQueryBuilder("source_template");
        $queryBuilder
            ->where('source_template.default = :isDefault')->setParameter('isDefault', true)
            ->setMaxResults(1);

        try {
            /** @var  SourceTemplate $result */
            $result = $queryBuilder->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            //impossible case, cuz we use setMaxResults(1)
            return null;
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return SourceTemplate|null
     */
    public function findOneByUserId(string $userId): ?SourceTemplate
    {
        $queryBuilder = $this->createQueryBuilder("source_template");
        $query        = $queryBuilder
            ->addSelect('man_hst')
            ->innerJoin('source_template.managerRelations', 'man_hst')
            ->where("man_hst.managerId = :userId")->setParameter("userId", $userId)
            ->setMaxResults(1)
            ->getQuery();

        /** @var SourceTemplate|null $result */
        try {
            $result = $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            //impossible case, cuz we use setMaxResults(1)
            return null;
        }

        return $result;
    }
}
