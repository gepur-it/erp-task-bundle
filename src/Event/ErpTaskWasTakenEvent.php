<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\ErpTaskBundle\Event;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CallTaskWasLockedEvent
 * @package GepurIt\ErpTaskBundle\Event
 * @codeCoverageIgnore
 */
class ErpTaskWasTakenEvent extends Event
{
    const EVENT_NAME = 'erp_task_was_taken';

    /** @var string */
    private $userId;

    /** @var \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface */
    private $callTask;

    /**
     * CallTaskWasTakenEvent constructor.
     *
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
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
     * @return \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface
     */
    public function getTask(): ErpTaskInterface
    {
        return $this->callTask;
    }
}
