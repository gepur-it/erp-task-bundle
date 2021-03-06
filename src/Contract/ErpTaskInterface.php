<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Contract;

/**
 * Interface CallTaskInterface
 * @package GepurIt\ErpTaskBundle\CallTask
 */
interface ErpTaskInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTaskId(): string;

    /**
     * @return string
     */
    public function getGroupId(): string;

    /**
     * @param string|null $userId
     */
    public function setLockedBy(string $userId = null): void;

    /**
     * @return null|string
     */
    public function getLockedBy(): ?string;

    /**
     * @param string|null $userId
     * @return array
     */
    public function getAvailableActions(?string $userId = null): array;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     */
    public function setStatus(string $status);

    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime;

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt(\DateTime $startAt);

    /**
     * @return \DateTime|null
     */
    public function getClosedAt(): ?\DateTime;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;
}
