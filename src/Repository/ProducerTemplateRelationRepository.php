<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GepurIt\ErpTaskBundle\Entity\ProducerTemplateRelation;

/**
 * Class ProducerTemplateRelationRepository
 * @package GepurIt\ErpTaskBundle\Repository
 * @method ProducerTemplateRelation[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method ProducerTemplateRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProducerTemplateRelation[] findAll()
 * @codeCoverageIgnore
 */
class ProducerTemplateRelationRepository extends EntityRepository
{
}
