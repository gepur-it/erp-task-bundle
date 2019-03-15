<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\CallTaskBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GepurIt\CallTaskBundle\Entity\SourceTemplateRelation;

/**
 * Class SourceTemplateRelationRepository
 * @package GepurIt\CallTaskBundle\Repository
 * @method SourceTemplateRelation[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method SourceTemplateRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceTemplateRelation[] findAll()
 * @codeCoverageIgnore
 */
class SourceTemplateRelationRepository extends EntityRepository
{
}
