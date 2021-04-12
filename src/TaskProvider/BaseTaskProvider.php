<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\TaskProvider;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProducerInterface;
use GepurIt\ErpTaskBundle\Contract\TaskProviderInterface;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarkerInterface;
use GepurIt\ErpTaskBundle\Entity\ManagerHasTaskProducer;
use GepurIt\ErpTaskBundle\Entity\ProducersTemplate;
use GepurIt\ErpTaskBundle\Event\ErpTaskWasTakenEvent;
use GepurIt\ErpTaskBundle\Repository\ManagerHasProducerRepository;
use GepurIt\ErpTaskBundle\Repository\ProducerTemplateRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BaseTaskProvider
 * @package GepurIt\ErpTaskBundle\TaskProvider
 */
class BaseTaskProvider
{
    private EntityManagerInterface $entityManager;
    private TaskMarkerInterface $taskMarker;
    private EventDispatcherInterface $eventDispatcher;

    /** @var TaskProviderInterface[] */
    private array $concreteTypeProviders = [];

    /**
     * CallTaskProvider constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param TaskMarkerInterface      $taskMarker
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TaskMarkerInterface $taskMarker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager   = $entityManager;
        $this->taskMarker      = $taskMarker;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $userId
     *
     * @return TaskProducerInterface[]
     */
    public function getUserTemplateProducers(string $userId): array
    {
        /** @var ProducerTemplateRepository $repo */
        $repo     = $this->entityManager->getRepository(ProducersTemplate::class);
        $template = $repo->findOneByUserId($userId);
        if (null === $template) {
            $template = $repo->getDefault();
        }
        $result = [];

        foreach ($template->getRelations() as $relation) {
            $result[] = $this->getTaskProvider($relation->getProducerType())->getProducer($relation->getProducerName());
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return TaskProducerInterface[]
     */
    public function getProducersByUserId(string $userId): array
    {
        /** @var ManagerHasProducerRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasTaskProducer::class);
        $relations  = $repository->findByUser($userId);

        $result = [];
        foreach ($relations as $relation) {
            $result[] = $this->getTaskProvider(
                $relation->getProducerType())
                ->getProducer($relation->getProducerName()
                );
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
            $this->eventDispatcher->dispatch(new ErpTaskWasTakenEvent($nextTask, $userId));
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
        $sources = $this->getProducersByUserId($userId);

        if (empty($sources)) {
            $sources = $this->getUserTemplateProducers($userId);
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
    public function getConcreteTask(string $taskType, string $taskId): ?ErpTaskInterface
    {
        $concreteProvider = $this->getTaskProvider($taskType);
        return $concreteProvider->findTask($taskId);
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

        $provider = $this->getTaskProvider($mark->getType());
        return $provider->findTask($mark->getTaskId());
    }

    /**
     * @param TaskProviderInterface $taskProvider
     */
    public function registerProvider(TaskProviderInterface $taskProvider): void
    {
        $this->concreteTypeProviders[$taskProvider->getType()] = $taskProvider;
    }

    /**
     * @param string $type
     *
     * @return TaskProviderInterface
     */
    public function getTaskProvider(string $type): TaskProviderInterface
    {
        return $this->concreteTypeProviders[$type];
    }

    /**
     * @param callable|null $filter
     * @return TaskProviderInterface[]|\Generator
     */
    public function getTaskProviders(?callable $filter = null): iterable
    {
        if (null === $filter) {
            $filter = function (TaskProviderInterface $provider): bool {
                return true;
            };
        }
        foreach ($this->concreteTypeProviders as $provider) {
            if ($filter($provider)) {
                yield $provider->getType() => $provider;
            }
        }
    }

    /**
     * @param callable|null $providerFilter
     * @param callable|null $sourceFilter
     *
     * @return TaskProducerInterface[]|\Generator
     */
    public function getProducers(?callable $providerFilter = null, ?callable $sourceFilter = null): iterable
    {
        foreach ($this->getTaskProviders($providerFilter) as $provider) {
            foreach ($provider->getProducers($sourceFilter) as $producer) {
                yield $producer;
            }
        }
    }
}
