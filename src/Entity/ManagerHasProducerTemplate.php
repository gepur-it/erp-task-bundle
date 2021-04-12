<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.08.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserTemplateRelation
 * @package GepurIt\ErpTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="erp_task_manager_has_template",
 * )
 * @ORM\Entity()
 *
 * @codeCoverageIgnore
 */
class ManagerHasProducerTemplate
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="manager_id", type="string", length=80, nullable=false, unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private string $managerId = '';

    /**
     * @var ProducersTemplate|null
     * @ORM\ManyToOne(targetEntity="\GepurIt\ErpTaskBundle\Entity\ProducersTemplate", inversedBy="managerRelations")
     * @ORM\JoinColumn(name="template", referencedColumnName="name")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private ?ProducersTemplate $template = null;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private ?\DateTime $createdAt = null;

    /**
     * ManagerHasProducerTemplate constructor.
     *
     * @param ProducersTemplate $template
     * @param string         $managerId
     */
    public function __construct(ProducersTemplate $template, string $managerId)
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
     * @return ProducersTemplate
     */
    public function getTemplate(): ProducersTemplate
    {
        return $this->template;
    }

    /**
     * @param ProducersTemplate $template
     */
    public function setTemplate(ProducersTemplate $template): void
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
