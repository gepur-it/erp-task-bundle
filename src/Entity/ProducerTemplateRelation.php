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
 *     name="call_task_template_relation",
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
     * @ORM\Column(name="source_name", type="string")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sourceName;

    /**
     * @var string
     * @ORM\Column(name="source_type", type="string")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sourceType;

    /**
     * @var int
     * @ORM\Column(name="priority", type="string")
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
        $this->template   = $template;
        $this->sourceName = $sourceName;
        $this->sourceType = $sourceType;
    }

    /**
     * @return string
     */
    public function getSourceName(): string
    {
        return $this->sourceName;
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
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }
}
