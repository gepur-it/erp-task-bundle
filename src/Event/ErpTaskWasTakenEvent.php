<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\ErpTaskBundle\Event;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class CallTaskWasLockedEvent
 * @package GepurIt\ErpTaskBundle\Event
 * @codeCoverageIgnore
 */
class ErpTaskWasTakenEvent extends Event
{
    /**
     * @deprecated
     * @var string
     */
    const EVENT_NAME = self::class;

    /** @var string */
    private $userId;

    /** @var ErpTaskInterface */
    private $callTask;

    /**
     * CallTaskWasTakenEvent constructor.
     *
     * @param ErpTaskInterface $callTask
     * @param string           $userId
     */
    public function __construct(ErpTaskInterface $callTask, string $userId)
    {
        $this->callTask = $callTask;
        $this->userId   = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return ErpTaskInterface
     */
    public function getTask(): ErpTaskInterface
    {
        return $this->callTask;
    }
}
