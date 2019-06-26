<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\ErpTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SourceToUserRelation
 * @package GepurIt\ErpTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="erp_task_manager_has_producer")
 * @ORM\Entity(repositoryClass="\GepurIt\ErpTaskBundle\Repository\ManagerHasProducerRepository")
 * @codeCoverageIgnore
 */
class ManagerHasTaskProducer
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="user_id", type="string", length=80, nullable=false)
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="producer_name", type="string", length=40, nullable=false)
     * @ORM\Id
     */
    private $producerName;

    /**
     * @var string
     * @ORM\Column(name="producer_type", type="string", length=40, nullable=false)
     * @ORM\Id
     */
    private $producerType;

    /**
     * @var int
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority = 0;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     * @ORM\Version()
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * SourceToUserRelation constructor.
     *
     * @param string $userId
     * @param string $producerName
     * @param string $producerType
     */
    public function __construct(string $userId, string $producerName, string $producerType)
    {
        $this->userId     = $userId;
        $this->producerName = $producerName;
        $this->producerType = $producerType;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
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
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getProducerType(): string
    {
        return $this->producerType;
    }

    /**
     * @param string $producerType
     */
    public function setProducerType(string $producerType): void
    {
        $this->producerType = $producerType;
    }
}
