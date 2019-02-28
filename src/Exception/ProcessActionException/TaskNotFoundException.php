<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\CallTaskBundle\Exception\ProcessActionException;

use GepurIt\CallTaskBundle\Exception\ProcessActionException;

/**
 * Class TaskNotFoundException
 * @package GepurIt\CallTaskBundle\Exception
 */
class TaskNotFoundException extends ProcessActionException
{
    /**
     * @var string
     */
    private $taskType;

    /**
     * @var string
     */
    private $taskId;

    /**
     * @var string
     */
    private $action;

    /**
     * TaskNotFoundException constructor.
     *
     * @param string $action
     * @param string $taskType
     * @param string $taskId
     */
    public function __construct(string $action, string $taskType, string $taskId)
    {
        parent::__construct("Action {$action} process error: task {$taskType}:{$taskId} not found", 404);
        $this->taskType = $taskType;
        $this->taskId   = $taskId;
        $this->action   = $action;
    }

    /**
     * @return string
     */
    public function getTaskType(): string
    {
        return $this->taskType;
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
    public function getAction(): string
    {
        return $this->action;
    }
}
