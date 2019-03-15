<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.08.18
 */

namespace GepurIt\CallTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserTemplateRelation
 * @package GepurIt\CallTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="call_task_manager_has_template",
 * )
 * @ORM\Entity()
 *
 * @codeCoverageIgnore
 */
class ManagerHasSourceTemplate
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="manager_id", type="string", length=80, nullable=false, unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $managerId;

    /**
     * @var SourceTemplate
     * @ORM\ManyToOne(targetEntity="GepurIt\CallTaskBundle\Entity\SourceTemplate", inversedBy="managerRelations")
     * @ORM\JoinColumn(name="template", referencedColumnName="name")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $template;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * ManagerHasSourceTemplate constructor.
     *
     * @param SourceTemplate $template
     * @param string         $managerId
     */
    public function __construct(SourceTemplate $template, string $managerId)
    {
        $this->template  = $template;
        $this->managerId = $managerId;
        $this->createdAt = new \DateTime("now");
    }

    /**
     * @return string
     */
    public function getManagerId(): string
    {
        return $this->managerId;
    }

    /**
     * @return SourceTemplate
     */
    public function getTemplate(): SourceTemplate
    {
        return $this->template;
    }

    /**
     * @param SourceTemplate $template
     */
    public function setTemplate(SourceTemplate $template)
    {
        $this->template = $template;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
