<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\ErpTaskBundle\ActionProcessor;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarkerInterface;
use GepurIt\ErpTaskBundle\Exception\ProcessActionException;
use GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseActionProcessor
 * @package GepurIt\ErpTaskBundle\ActionProcessor
 */
class BaseActionProcessor implements BaseActionProcessorInterface
{
    /** @var \GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider */
    private $callTaskProvider;

    /** @var TaskMarkerInterface */
    private $taskMarker;

    /** @var ValidatorInterface */
    private $validator;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * BaseActionProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param BaseTaskProvider       $callTaskProvider
     * @param TaskMarkerInterface    $taskMarker
     * @param ValidatorInterface     $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        BaseTaskProvider $callTaskProvider,
        TaskMarkerInterface $taskMarker,
        ValidatorInterface $validator
    ) {
        $this->callTaskProvider = $callTaskProvider;
        $this->taskMarker       = $taskMarker;
        $this->validator        = $validator;
        $this->entityManager    = $entityManager;
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
     * @return \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface|null
     */
    public function processAction(
        string $action,
        string $taskType,
        string $taskId,
        string $userId,
        array $params,
        string $message = ''
    ): ErpTaskInterface {
        $taskProvider = $this->callTaskProvider->getTaskProvider($taskType);
        $task         = $taskProvider->findTask($taskId);
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

        $processor        = $taskProvider->getActionProcessor();
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
