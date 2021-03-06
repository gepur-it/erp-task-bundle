<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:46
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\Stubs;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use DateTime;

/**
 * Class TestCallTask
 * @package GepurIt\ErpTaskBundle\Tests\Stubs
 */
class TestErpTask implements ErpTaskInterface
{
    const TYPE = 'test';

    private string $taskId;
    private string $clientId = 't';
    private ?DateTime $closedAt = null;
    private ?string $lockedBy = null;

    /**
     * TestCallTask constructor.
     * @param string $taskId
     */
    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
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
    public function getGroupId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string|null $userId
     */
    public function setLockedBy(string $userId = null): void
    {
        $this->lockedBy = $userId;
    }

    /**
     * @return null|string
     */
    public function getLockedBy(): ?string
    {
        return null;
    }

    /**
     * @param string|null $userId
     * @return array
     */
    public function getAvailableActions(?string $userId = null): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return 'test';
    }

    /**
     * @return \DateTime|null
     */
    public function getClosedAt(): ?\DateTime
    {
        return $this->closedAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime
    {
        return new \DateTime();
    }

    /** @return \DateTime|null */
    public function getCreatedAt(): ?\DateTime
    {
        return new \DateTime();
    }

    /**
     * @param null|string $status
     *
     * @return void
     */
    public function markCompleted(?string $status = null)
    {
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt(\DateTime $startAt)
    {
        //do nothing
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        //do nothing
    }

    /**
     * @param null|string $status
     *
     * @return void
     */
    public function markReset(?string $status = null)
    {
        //do nothing
    }

    /**
     * @param null|string $status
     *
     * @return void
     */
    public function markStarted(?string $status = null)
    {
        //do nothing
    }
}
