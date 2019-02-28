<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\CallTaskBundle\ActionProcessor;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\CallTaskBundle\CallTaskSource\CallTaskProvider;
use GepurIt\CallTaskBundle\CurrentTaskMarker\CurrentTaskMarkerInterface;
use GepurIt\CallTaskBundle\Exception\ProcessActionException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseActionProcessor
 * @package GepurIt\CallTaskBundle\ActionProcessor
 */
class BaseActionProcessor implements BaseActionProcessorInterface
{
    /** @var CallTaskProvider */
    private $callTaskProvider;

    /** @var CurrentTaskMarkerInterface */
    private $taskMarker;

    /** @var ActionProcessorRegistry */
    private $processorRegistry;

    /** @var ValidatorInterface */
    private $validator;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * BaseActionProcessor constructor.
     *
     * @param EntityManagerInterface     $entityManager
     * @param CallTaskProvider           $callTaskProvider
     * @param CurrentTaskMarkerInterface $taskMarker
     * @param ActionProcessorRegistry    $processorRegistry
     * @param ValidatorInterface         $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CallTaskProvider $callTaskProvider,
        CurrentTaskMarkerInterface $taskMarker,
        ActionProcessorRegistry $processorRegistry,
        ValidatorInterface $validator
    ) {
        $this->callTaskProvider  = $callTaskProvider;
        $this->taskMarker        = $taskMarker;
        $this->processorRegistry = $processorRegistry;
        $this->validator         = $validator;
        $this->entityManager     = $entityManager;
    }

    /**
     * @param string $action
     * @param string $taskType
     * @param string $taskId
     * @param string $userId
     * @param array  $params
     * @param string $message
     *
     * @throws ProcessActionException\UnprocessableActionEntityException
     * @throws ProcessActionException\ValidationException
     * @throws ProcessActionException\ActionNotAvailableException
     * @throws ProcessActionException\TaskNotFoundException
     *
     * @return CallTaskInterface|null
     */
    public function processAction(
        string $action,
        string $taskType,
        string $taskId,
        string $userId,
        array $params,
        string $message = ''
    ): CallTaskInterface {
        $task = $this->callTaskProvider->getConcreteTask($taskType, $taskId);
        if (null === $task) {
            throw new ProcessActionException\TaskNotFoundException($action, $taskType, $taskId);
        }

        if (!in_array($action, $task->getAvailableActions())) {
            throw new ProcessActionException\ActionNotAvailableException($action, $taskType, $taskId);
        }

        $taskLock = $this->taskMarker->getMarkByTask($task);
        if (null !== $taskLock && $taskLock->getUserId() !== $userId) {
            throw new ProcessActionException\LockedByAnotherUserException(
                $action,
                $taskType,
                $taskId,
                $taskLock->getUserId()
            );
        }

        $processor        = $this->processorRegistry->get($taskType);
        $supportedActions = $processor->getSupportedActions();
        $actionClass      = $supportedActions[$action];

        /** @var ActionInterface $actionEntity */
        $actionEntity = new $actionClass($taskId, $taskType, $userId, $params);

        $errors = $this->validator->validate($actionEntity);
        if ($errors->count() > 0) {
            throw new ProcessActionException\ValidationException($errors);
        }

        $actionEntity->setMessage($message);
        $this->entityManager->persist($actionEntity);

        $processor->processAction($actionEntity, $task);

        return $task;
    }
}
