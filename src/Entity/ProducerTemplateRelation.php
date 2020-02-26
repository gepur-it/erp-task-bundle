<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\ErpTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProducerTemplateRelation
 * @package GepurIt\ErpTaskBundle\Entity
 * @ORM\Table(
 *     name="erp_task_template_relation",
 *     indexes={
 *          @ORM\Index(name="priority_idx", columns={"priority"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="GepurIt\ErpTaskBundle\Repository\ProducerTemplateRelationRepository",
 * )
 * @codeCoverageIgnore
 */
class ProducerTemplateRelation
{
    /**
     * @var string
     * @ORM\Column(name="producer_name", type="string")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $producerName;

    /**
     * @var string
     * @ORM\Column(name="producer_type", type="string")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $producerType;

    /**
     * @var int
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority = 0;

    /**
     * @var ProducersTemplate
     * @ORM\ManyToOne(targetEntity="GepurIt\ErpTaskBundle\Entity\ProducersTemplate", inversedBy="relations")
     * @ORM\JoinColumn(name="template_name", referencedColumnName="name")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $template;

    /**
     * ProducerTemplateRelation constructor.
     *
     * @param ProducersTemplate $template
     * @param string         $sourceName
     * @param string         $sourceType
     */
    public function __construct(ProducersTemplate $template, string $sourceName, string $sourceType)
    {
        $this->template     = $template;
        $this->producerName = $sourceName;
        $this->producerType = $sourceType;
    }

    /**
     * @return string
     */
    public function getProducerName(): string
    {
        return $this->producerName;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return ProducersTemplate
     */
    public function getTemplate(): ProducersTemplate
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getProducerType(): string
    {
        return $this->producerType;
    }
}
