<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\CallTaskBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SourceTemplate
 * @package GepurIt\CallTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="call_task_template",
 *     indexes={
 *          @ORM\Index(name="is_default_idx", columns={"is_default"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="GepurIt\CallTaskBundle\Repository\SourceTemplateRepository",
 * )
 * @codeCoverageIgnore
 *
 * @UniqueEntity("label")
 * @UniqueEntity("name")
 */
class SourceTemplate
{
    /**
     * @var string
     * @ORM\Column(name="label", type="string", length=250)
     * @Assert\NotBlank()
     */
    private $label = '';

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="is_default")
     */
    private $default = false;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=20)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @Assert\NotBlank()
     */
    private $name = '';

    /**
     * @var ArrayCollection|SourceTemplateRelation[]
     * @ORM\OneToMany(
     *     targetEntity="GepurIt\CallTaskBundle\Entity\SourceTemplateRelation",
     *     mappedBy="template",
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    private $relations;

    /**
     * @var ArrayCollection|ManagerHasSourceTemplate[]
     * @ORM\OneToMany(
     *     targetEntity="GepurIt\CallTaskBundle\Entity\ManagerHasSourceTemplate",
     *     mappedBy="template",
     *     cascade={"persist"}
     * )
     */
    private $managerRelations;

    /**
     * SourceTemplate constructor.
     *
     * @param string $name
     * @param string $label
     */
    public function __construct(string $name, string $label)
    {
        $this->name             = $name;
        $this->label            = $label;
        $this->relations        = new ArrayCollection();
        $this->managerRelations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|SourceTemplateRelation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param ArrayCollection|SourceTemplateRelation[] $relations
     */
    public function setRelations(ArrayCollection $relations): void
    {
        $this->relations = $relations;
    }

    /**
     * @param SourceTemplateRelation $relation
     */
    public function addRelation(SourceTemplateRelation $relation)
    {
        $this->relations->add($relation);
    }

    /**
     * @param SourceTemplateRelation $relation
     */
    public function removeRelation(SourceTemplateRelation $relation)
    {
        $this->relations->removeElement($relation);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     */
    public function setDefault(bool $default): void
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection|ManagerHasSourceTemplate[]
     */
    public function getManagerRelations()
    {
        return $this->managerRelations;
    }
}
