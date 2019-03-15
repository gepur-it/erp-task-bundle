<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\ErpTaskBundle\TaskProvider;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProviderInterface;
use GepurIt\ErpTaskBundle\CurrentTaskMarker\CurrentTaskMarkerInterface;
use GepurIt\ErpTaskBundle\Entity\ManagerHasTaskProducer;
use GepurIt\ErpTaskBundle\Entity\ProducersTemplate;
use GepurIt\ErpTaskBundle\Event\ErpTaskWasTakenEvent;
use GepurIt\ErpTaskBundle\Repository\ManagerHasProducerRepository;
use GepurIt\ErpTaskBundle\Repository\ProducerTemplateRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CallTaskProvider
 * @package ErpTaskBundle
 */
class BaseTaskProvider
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CurrentTaskMarkerInterface */
    private $taskMarker;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var TaskProviderInterface[] */
    private $concreteTypeProviders = [];

    /**
     * CallTaskProvider constructor.
     *
     * @param EntityManagerInterface        $entityManager
     * @param CurrentTaskMarkerInterface    $taskMarker
     * @param EventDispatcherInterface      $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CurrentTaskMarkerInterface $taskMarker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager    = $entityManager;
        $this->taskMarker       = $taskMarker;
        $this->eventDispatcher  = $eventDispatcher;
    }

    /**
     * @param string $userId
     *
     * @return TaskProducerInterface[]
     */
    public function getUserTemplateSources(string $userId)
    {
        /** @var ProducerTemplateRepository $repo */
        $repo     = $this->entityManager->getRepository(ProducersTemplate::class);
        $template = $repo->findOneByUserId($userId);
        if (null === $template) {
            $template = $repo->getDefault();
        }
        $result = [];

        foreach ($template->getRelations() as $relation) {
            $result[] = $this->getTypeProvider($relation->getSourceType())->getSource($relation->getSourceName());
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return TaskProducerInterface[]
     */
    public function getSourcesByUserId(string $userId): array
    {
        /** @var ManagerHasProducerRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasTaskProducer::class);
        $relations  = $repository->findByUser($userId);

        $result = [];
        foreach ($relations as $relation) {
            $result[] = $this->getTypeProvider($relation->getSourceType())->getSource($relation->getSourceName());
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return ErpTaskInterface|null
     */
    public function detectForUser(string $userId): ? ErpTaskInterface
    {
        $currentTask = $this->getLockedByUser($userId);

        if (null !== $currentTask) {
            return $currentTask;
        }
        $nextTask = $this->determineNextTask($userId);

        if (null !== $nextTask) {
            $this->eventDispatcher->dispatch(
                ErpTaskWasTakenEvent::EVENT_NAME,
                new ErpTaskWasTakenEvent($nextTask, $userId)
            );
        }

        return $nextTask;
    }

    /**
     * @param string $userId
     *
     * @return ErpTaskInterface|null
     */
    public function determineNextTask(string $userId): ?ErpTaskInterface
    {
        $sources = $this->getSourcesByUserId($userId);

        if (empty($sources)) {
            $sources = $this->getUserTemplateSources($userId);
        }

        foreach ($sources as $source) {
            $task = $source->getNext();
            if (null !== $task) {
                return $task;
            }
        }

        return null;
    }

    /**
     * @param string $taskType
     * @param string $taskId
     *
     * @return ErpTaskInterface|null
     */
    public function getConcreteTask(string $taskType, string $taskId)
    {
        $concreteProvider = $this->getTypeProvider($taskType);
        $callTask       = $concreteProvider->find($taskId);

        return $callTask;
    }

    /**
     * @param string $userId
     *
     * @return ErpTaskInterface|null
     */
    public function getLockedByUser(string $userId): ?ErpTaskInterface
    {
        $mark = $this->taskMarker->getTaskMark($userId);

        if (null === $mark) {
            return null;
        }

        $provider = $this->getTypeProvider($mark->getType());
        $task     = $provider->find($mark->getTaskId());

        return $task;
    }

    /**
     * @return TaskProviderInterface[]
     */
    public function getAllProviderTypes(): array
    {
        return $this->concreteTypeProviders;
    }

    /**
     * @param TaskProviderInterface $taskProvider
     */
    public function registerProvider(TaskProviderInterface $taskProvider)
    {
        $this->concreteTypeProviders[$taskProvider->getType()] = $taskProvider;
    }

    /**
     * @param string $type
     *
     * @return TaskProviderInterface
     */
    public function getTypeProvider(string $type): ?TaskProviderInterface
    {
        return $this->concreteTypeProviders[$type];
    }

    /**
     * @return TaskProviderInterface[]
     */
    public function getAllTypeProviders(): array
    {
        return $this->concreteTypeProviders;
    }
}
