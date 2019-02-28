<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 19.06.18
 * Time: 16:40
 */

namespace GepurIt\CallTaskBundle\Exception;

/**
 * Class ProcessActionException
 * @package GepurIt\CallTaskBundle\Exception
 */
class ProcessActionException extends CallTaskException
{
    protected $errors = null;

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
