<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.07.18
 */

namespace GepurIt\ErpTaskBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProducersTemplate
 * @package GepurIt\ErpTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="erp_task_template",
 *     indexes={
 *          @ORM\Index(name="is_default_idx", columns={"is_default"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="\GepurIt\ErpTaskBundle\Repository\ProducerTemplateRepository",
 * )
 * @codeCoverageIgnore
 *
 * @UniqueEntity("label")
 * @UniqueEntity("name")
 */
class ProducersTemplate
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
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="GepurIt\ErpTaskBundle\Entity\ProducerTemplateRelation",
     *     mappedBy="template",
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    private $relations;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="ManagerHasProducerTemplate",
     *     mappedBy="template",
     *     cascade={"persist"}
     * )
     */
    private $managerRelations;

    /**
     * ProducersTemplate constructor.
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
     * @return ArrayCollection|ProducerTemplateRelation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param ArrayCollection $relations
     */
    public function setRelations(ArrayCollection $relations): void
    {
        $this->relations = $relations;
    }

    /**
     * @param ProducerTemplateRelation $relation
     */
    public function addRelation(ProducerTemplateRelation $relation): void
    {
        $this->relations->add($relation);
    }

    /**
     * @param ProducerTemplateRelation $relation
     */
    public function removeRelation(ProducerTemplateRelation $relation): void
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
     * @return ArrayCollection|ManagerHasProducerTemplate[]
     */
    public function getManagerRelations()
    {
        return $this->managerRelations;
    }
}
