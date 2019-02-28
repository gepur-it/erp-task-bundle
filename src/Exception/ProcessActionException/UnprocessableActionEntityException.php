<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 08.02.19
 */

namespace GepurIt\CallTaskBundle\Exception\ProcessActionException;

use GepurIt\CallTaskBundle\Exception\ProcessActionException;

/**
 * Class CodedCallTaskException
 * @package GepurIt\CallTaskBundle\Exception
 */
class UnprocessableActionEntityException extends ProcessActionException
{
    /**
     * CodedCallTaskException constructor.
     *
     * @param string $field
     * @param string $message
     * @param int    $code
     */
    public function __construct(string $field, string $message, int $code)
    {
        $this->errors[] = [
            'code' => $code,
            'field' => $field,
            'message' => $message,
        ];

        parent::__construct('Unprocessable Action Entity', 422, null);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
