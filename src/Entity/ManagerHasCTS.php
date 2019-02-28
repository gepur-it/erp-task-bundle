<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SourceToUserRelation
 * @package GepurIt\CallTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="call_task_manager_has_cts")
 * @ORM\Entity(repositoryClass="GepurIt\CallTaskBundle\Repository\ManagerHasCTSRepository")
 * @codeCoverageIgnore
 */
class ManagerHasCTS
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="user_id", type="string", length=80, nullable=false)
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="source_name", type="string", length=40, nullable=false)
     * @ORM\Id
     */
    private $sourceName;

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
     * @param string $sourceName
     */
    public function __construct(string $userId, string $sourceName)
    {
        $this->userId     = $userId;
        $this->sourceName = $sourceName;
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
}
