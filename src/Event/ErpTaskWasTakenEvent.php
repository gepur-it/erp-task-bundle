<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\Event;

use GepurIt\CallTaskBundle\Contract\ErpTaskInterface;
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

    /** @var \GepurIt\CallTaskBundle\Contract\ErpTaskInterface */
    private $callTask;

    /**
     * CallTaskWasTakenEvent constructor.
     *
     * @param \GepurIt\CallTaskBundle\Contract\ErpTaskInterface $callTask
     * @param string                                            $userId
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
     * @return \GepurIt\CallTaskBundle\Contract\ErpTaskInterface
     */
    public function getCallTask(): ErpTaskInterface
    {
        return $this->callTask;
    }
}
