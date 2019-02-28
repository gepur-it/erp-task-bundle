<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.08.18
 * Time: 11:52
 */

namespace GepurIt\CallTaskBundle\Dynamic;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\CallTaskBundle\CallTaskSource\SourceInterface;

/**
 * Interface DynamicSourceInterface
 * @package GepurIt\CallTaskBundle\Dynamic
 */
interface DynamicSourceInterface extends SourceInterface
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
