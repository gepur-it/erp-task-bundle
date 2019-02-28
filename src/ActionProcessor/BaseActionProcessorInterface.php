<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\CallTaskBundle\ActionProcessor;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\CallTaskBundle\Exception\ProcessActionException;

/**
 * Class BaseActionProcessorInterface
 * @package GepurIt\CallTaskBundle\ActionProcessor
 */
interface BaseActionProcessorInterface
{
    /**
     * @param string $action
     * @param string $taskType
     * @param string $taskId
     * @param string $userId
     * @param array  $params
     * @param string $message
     *
     * @throws ProcessActionException
     *
     * @return CallTaskInterface
     */
    public function processAction(
        string $action,
        string $taskType,
        string $taskId,
        string $userId,
        array $params,
        string $message = ''
    ): CallTaskInterface;
}
