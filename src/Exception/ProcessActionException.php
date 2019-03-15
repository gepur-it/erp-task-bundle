<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 19.06.18
 * Time: 16:40
 */

namespace GepurIt\ErpTaskBundle\Exception;

/**
 * Class ProcessActionException
 * @package GepurIt\ErpTaskBundle\Exception
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
