<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 31.07.18
 */

namespace GepurIt\CallTaskBundle\Exception;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Class MarkNotFoundException
 * @package GepurIt\CallTaskBundle\Exception
 * @codeCoverageIgnore
 */
class TaskMarkNotFoundException extends CallTaskException
{
    public function __construct(CallTaskInterface $callTask)
    {
        $message = "Task {$callTask->getType()}:{$callTask->getTaskId()} not marked";
        return parent::__construct($message);
    }
}
