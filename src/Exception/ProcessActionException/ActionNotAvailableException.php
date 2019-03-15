<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\ErpTaskBundle\Exception\ProcessActionException;

use GepurIt\ErpTaskBundle\Exception\ProcessActionException;

/**
 * Class ActionNotAvailableException
 * @package GepurIt\ErpTaskBundle\Exception\ProcessActionException
 */
class ActionNotAvailableException extends ProcessActionException
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
     * ActionNotAvailableException constructor.
     *
     * @param string $action
     * @param string $taskType
     * @param string $taskId
     */
    public function __construct(string $action, string $taskType, string $taskId)
    {
        parent::__construct("Action {$action} is not available for task {$taskType}:{$taskId}", 403);
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
