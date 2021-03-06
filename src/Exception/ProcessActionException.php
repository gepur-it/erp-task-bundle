<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 19.06.18
 * Time: 16:40
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Exception;

use Throwable;

/**
 * Class ProcessActionException
 * @package GepurIt\ErpTaskBundle\Exception
 */
class ProcessActionException extends CallTaskException
{
    /**
     * @var array|null
     */
    protected $errors = null;

    public function __construct(string $message = "", int $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
