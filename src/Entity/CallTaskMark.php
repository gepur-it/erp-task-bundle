<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 15.05.18
 */

namespace GepurIt\ErpTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GepurIt\ErpTaskBundle\CurrentTaskMarker\CurrentTaskMarkInterface;

/**
 * Class TaskMark
 * @package GepurIt\ErpTaskBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="call_task_mark",
 *     indexes={
 *         @ORM\Index(name="user_id_idx", columns={"user_id"}),
 *         @ORM\Index(name="client_id_idx", columns={"client_id"})
 *     }
 * )
 * @ORM\Entity(
 *     repositoryClass="\GepurIt\ErpTaskBundle\Repository\ErpTaskMarkRepository",
 * )
 * @codeCoverageIgnore
 */
class CallTaskMark implements CurrentTaskMarkInterface
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="task_id", type="guid", nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $taskId;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="task_type", type="string", length=40, nullable=false)
     */
    private $taskType;

    /**
     * @var string
     * @ORM\Column(name="user_id", type="string", length=80, nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_id", type="string", length=36, nullable=false)
     */
    private $clientId = '';

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * TaskMark constructor.
     * @param string $taskId
     * @param string $taskType
     * @param string $userId
     * @param string $clientId
     */
    public function __construct(string $taskId, string $taskType, string $userId, string $clientId)
    {
        $this->taskId   = $taskId;
        $this->taskType = $taskType;
        $this->userId   = $userId;
        $this->clientId = $clientId;
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
    public function getClientId(): string
    {
        return $this->clientId;
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
    public function prePersist()
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
     */
    public function transferTo(string $userId): void
    {
        $this->createdAt = new \DateTime();
        $this->userId = $userId;
    }
}
