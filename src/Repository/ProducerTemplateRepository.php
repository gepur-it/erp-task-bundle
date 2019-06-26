<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\ErpTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GepurIt\ErpTaskBundle\Entity\ProducersTemplate;

/**
 * Class ProducerTemplateRepository
 * @package GepurIt\ErpTaskBundle\Repository
 * @method ProducersTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @codeCoverageIgnore
 */
class ProducerTemplateRepository extends EntityRepository
{
    /**
     * @return ProducersTemplate
     */
    public function getDefault(): ProducersTemplate
    {
        $queryBuilder = $this->createQueryBuilder("source_template");
        $queryBuilder
            ->where('source_template.default = :isDefault')->setParameter('isDefault', true)
            ->setMaxResults(1);

        /** @var  ProducersTemplate[] $result */
        $result = $queryBuilder->getQuery()->execute();

        return array_shift($result);
    }

    /**
     * @param string $userId
     *
     * @return ProducersTemplate|null
     */
    public function findOneByUserId(string $userId): ?ProducersTemplate
    {
        $queryBuilder = $this->createQueryBuilder("source_template");
        $query        = $queryBuilder
            ->addSelect('man_hst')
            ->innerJoin('source_template.managerRelations', 'man_hst')
            ->where("man_hst.managerId = :userId")->setParameter("userId", $userId)
            ->setMaxResults(1)
            ->getQuery();

        /** @var  ProducersTemplate[] $result */
        $result = $query->execute();

        return array_shift($result);
    }
}
