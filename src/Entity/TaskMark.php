<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 15.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarkInterface;

/**
 * Class TaskMark
 * @package GepurIt\ErpTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="erp_task_mark",
 *     indexes={
 *         @ORM\Index(name="user_id_idx", columns={"user_id"}),
 *         @ORM\Index(name="group_key_idx", columns={"group_key"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="\GepurIt\ErpTaskBundle\Repository\ErpTaskMarkRepository",
 * )
 * @codeCoverageIgnore
 */
class TaskMark implements TaskMarkInterface
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="task_id", type="guid", nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private string $taskId;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="task_type", type="string", length=40, nullable=false)
     */
    private string $taskType;

    /**
     * @var string
     * @ORM\Column(name="user_id", type="string", length=80, nullable=false)
     */
    private string $userId;

    /**
     * Group key, used for selections and filters,
     * like "client unique id" or something else
     * @var string
     *
     * @ORM\Column(name="group_key", type="string", length=36, nullable=false)
     */
    private string $groupKey = '';

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private ?\DateTime $createdAt = null;

    /**
     * TaskMark constructor.
     * @param string $taskId
     * @param string $taskType
     * @param string $userId
     * @param string $groupKey
     */
    public function __construct(string $taskId, string $taskType, string $userId, string $groupKey)
    {
        $this->taskId   = $taskId;
        $this->taskType = $taskType;
        $this->userId   = $userId;
        $this->groupKey = $groupKey;
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
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
    public function getGroupKey(): string
    {
        return $this->groupKey;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
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
    public function getType(): string
    {
        return $this->taskType;
    }

    /**
     * @param string $userId
     * @throws \Exception
     */
    public function transferTo(string $userId): void
    {
        $this->createdAt = new \DateTime();
        $this->userId = $userId;
    }
}
