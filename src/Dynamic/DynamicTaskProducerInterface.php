<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.08.18
 * Time: 11:52
 */

namespace GepurIt\ErpTaskBundle\Dynamic;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;

/**
 * Interface DynamicSourceInterface
 * @package GepurIt\ErpTaskBundle\Dynamic
 */
interface DynamicTaskProducerInterface extends TaskProducerInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void;

    /**
     * @return string
     */
    public function getMark(): string;

    /**
     * @return string
     */
    public function getType(): string;
}
