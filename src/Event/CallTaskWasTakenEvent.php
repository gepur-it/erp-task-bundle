<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\Event;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CallTaskWasLockedEvent
 * @package GepurIt\CallTaskBundle\Event
 * @codeCoverageIgnore
 */
class CallTaskWasTakenEvent extends Event
{
    const EVENT_NAME = 'call_task_was_locked';

    /** @var string */
    private $userId;

    /** @var CallTaskInterface */
    private $callTask;

    /**
     * CallTaskWasTakenEvent constructor.
     *
     * @param CallTaskInterface $callTask
     * @param string            $userId
     */
    public function __construct(CallTaskInterface $callTask, string $userId)
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
     * @return CallTaskInterface
     */
    public function getCallTask(): CallTaskInterface
    {
        return $this->callTask;
    }
}
