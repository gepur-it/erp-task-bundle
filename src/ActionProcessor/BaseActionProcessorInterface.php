<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\ErpTaskBundle\ActionProcessor;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Exception\ProcessActionException;

/**
 * Class BaseActionProcessorInterface
 * @package GepurIt\ErpTaskBundle\ActionProcessor
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
     * @return \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface
     */
    public function processAction(
        string $action,
        string $taskType,
        string $taskId,
        string $userId,
        array $params,
        string $message = ''
    ): ErpTaskInterface;
}
