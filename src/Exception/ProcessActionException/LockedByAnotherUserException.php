<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Exception\ProcessActionException;

use GepurIt\ErpTaskBundle\Exception\ProcessActionException;

/**
 * Class LockedByAnotherUserException
 * @package GepurIt\ErpTaskBundle\Exception\ProcessActionException
 */
class LockedByAnotherUserException extends ProcessActionException
{
    private string $action;
    private string $taskType;
    private string $taskId;
    private string $userId;

    /**
     * LockedByAnotherUserException constructor.
     *
     * @param string $action
     * @param string $taskType
     * @param string $taskId
     * @param string $userId
     */
    public function __construct(string $action, string $taskType, string $taskId, string $userId)
    {
        $this->action = $action;
        $this->taskType = $taskType;
        $this->taskId = $taskId;
        $this->userId = $userId;
        parent::__construct("Action {$action} is for task {$taskType}:{$taskId} is locked by {$userId}", 409);

        $this->errors = [
            [
                'field' => 'managerId',
                'value' => $userId
            ]
        ];
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
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
    public function getUserId(): string
    {
        return $this->userId;
    }
}
