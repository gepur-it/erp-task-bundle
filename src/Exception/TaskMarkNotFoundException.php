<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 31.07.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Exception;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Class MarkNotFoundException
 * @package GepurIt\ErpTaskBundle\Exception
 * @codeCoverageIgnore
 */
class TaskMarkNotFoundException extends CallTaskException
{
    public function __construct(ErpTaskInterface $callTask)
    {
        $message = "Task {$callTask->getType()}:{$callTask->getTaskId()} not marked";
        return parent::__construct($message);
    }
}
