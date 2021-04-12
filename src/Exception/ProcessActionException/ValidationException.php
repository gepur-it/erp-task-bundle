<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 07.12.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Exception\ProcessActionException;

use GepurIt\ErpTaskBundle\Exception\ProcessActionException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class ValidationException
 * @package GepurIt\ErpTaskBundle\Exception
 */
class ValidationException extends ProcessActionException
{
    /** @var ConstraintViolationListInterface */
    private $violationList;

    /**
     * ValidationException constructor.
     *
     * @param ConstraintViolationListInterface $violationList
     * @param string                  $message
     * @param int                     $code
     * @param Throwable|null          $previous
     */
    public function __construct(
        ConstraintViolationListInterface $violationList,
        string $message = "",
        int $code = 400,
        Throwable $previous = null
    ) {
        $this->violationList = $violationList;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array
    {
        $formattedErrors = [];
        /** @var ConstraintViolation $item */
        foreach ($this->getViolationList() as $item) {
            $formattedErrors[] = ['field' => $item->getPropertyPath(), 'message' => $item->getMessage()];
        }

        return $formattedErrors;
    }
}
