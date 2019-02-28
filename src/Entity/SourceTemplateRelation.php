<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\CallTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SourceTemplateRelation
 * @package GepurIt\CallTaskBundle\Entity
 * @ORM\Table(
 *     name="call_task_template_relation",
 *     indexes={
 *          @ORM\Index(name="priority_idx", columns={"priority"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="GepurIt\CallTaskBundle\Repository\SourceTemplateRelationRepository",
 * )
 * @codeCoverageIgnore
 */
class SourceTemplateRelation
{
    /**
     * @var string
     * @ORM\Column(name="source_name", type="string")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sourceName;

    /**
     * @var int
     * @ORM\Column(name="priority", type="string")
     */
    private $priority = 0;

    /**
     * @var SourceTemplate
     * @ORM\ManyToOne(targetEntity="GepurIt\CallTaskBundle\Entity\SourceTemplate", inversedBy="relations")
     * @ORM\JoinColumn(name="template_name", referencedColumnName="name")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $template;

    /**
     * SourceTemplateRelation constructor.
     *
     * @param SourceTemplate $template
     * @param string         $sourceName
     */
    public function __construct(SourceTemplate $template, string $sourceName)
    {
        $this->template   = $template;
        $this->sourceName = $sourceName;
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
}
