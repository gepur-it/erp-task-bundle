<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.05.18
 */

namespace GepurIt\CallTaskBundle\CallTaskSource;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;
use GepurIt\CallTaskBundle\CurrentTaskMarker\CurrentTaskMarkerInterface;
use GepurIt\CallTaskBundle\Dynamic\DynamicSourceProviderRegistry;
use GepurIt\CallTaskBundle\Entity\ManagerHasCTS;
use GepurIt\CallTaskBundle\Entity\SourceTemplate;
use GepurIt\CallTaskBundle\Event\CallTaskWasTakenEvent;
use GepurIt\CallTaskBundle\Repository\ManagerHasCTSRepository;
use GepurIt\CallTaskBundle\Repository\SourceTemplateRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yawa20\RegistryBundle\Registrable\RegistrableInterface;
use Yawa20\RegistryBundle\Registry\SimpleRegistry;

/**
 * Class CallTaskProvider
 * @package CallTaskBundle
 * @method add(SourceInterface $item): void
 */
class CallTaskProvider extends SimpleRegistry
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CurrentTaskMarkerInterface */
    private $taskMarker;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var ConcreteCallTaskTypeProviderInterface[] */
    private $concreteTypeProviders = [];

    /** @var DynamicSourceProviderRegistry */
    private $providerRegistry;

    /**
     * initialization key,
     * used for lazy loading (because we do not want to ask database is construct)
     * @var bool
     */
    private $isInitialized = false;

    /**
     * CallTaskProvider constructor.
     *
     * @param EntityManagerInterface        $entityManager
     * @param CurrentTaskMarkerInterface    $taskMarker
     * @param EventDispatcherInterface      $eventDispatcher
     * @param DynamicSourceProviderRegistry $providerRegistry
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CurrentTaskMarkerInterface $taskMarker,
        EventDispatcherInterface $eventDispatcher,
        DynamicSourceProviderRegistry $providerRegistry
    ) {
        $this->entityManager    = $entityManager;
        $this->taskMarker       = $taskMarker;
        $this->eventDispatcher  = $eventDispatcher;
        $this->providerRegistry = $providerRegistry;
        parent::__construct(SourceInterface::class);
    }

    /**
     * @param string $userId
     *
     * @return SourceInterface[]
     */
    public function getUserTemplateSources(string $userId)
    {
        /** @var SourceTemplateRepository $repo */
        $repo     = $this->entityManager->getRepository(SourceTemplate::class);
        $template = $repo->findOneByUserId($userId);
        if (null === $template) {
            $template = $repo->getDefault();
        }
        $result = [];

        foreach ($template->getRelations() as $relation) {
            $result[] = $this->getSource($relation->getSourceName());
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return SourceInterface[]
     */
    public function getSourcesByUserId(string $userId): array
    {
        /** @var ManagerHasCTSRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasCTS::class);
        $relations  = $repository->findByUser($userId);

        $result = [];
        foreach ($relations as $relation) {
            $result[] = $this->getSource($relation->getSourceName());
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return CallTaskInterface|null
     */
    public function detectForUser(string $userId): ? CallTaskInterface
    {
        $currentTask = $this->getLockedByUser($userId);

        if (null !== $currentTask) {
            return $currentTask;
        }
        $nextTask = $this->determineNextTask($userId);

        if (null !== $nextTask) {
            $this->eventDispatcher->dispatch(
                CallTaskWasTakenEvent::EVENT_NAME,
                new CallTaskWasTakenEvent($nextTask, $userId)
            );
        }

        return $nextTask;
    }

    /**
     * @param string $userId
     *
     * @return CallTaskInterface|null
     */
    public function determineNextTask(string $userId): ?CallTaskInterface
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
     * @return CallTaskInterface|null
     */
    public function getConcreteTask(string $taskType, string $taskId)
    {
        $callTaskSource = $this->getTypeProvider($taskType);
        $callTask       = $callTaskSource->find($taskId);

        return $callTask;
    }

    /**
     * @param string $userId
     *
     * @return CallTaskInterface|null
     */
    public function getLockedByUser(string $userId): ?CallTaskInterface
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
     * @return ConcreteCallTaskTypeProviderInterface[]
     */
    public function getAllProviderTypes(): array
    {
        return $this->concreteTypeProviders;
    }

    /**
     * @param string $key
     *
     * @return SourceInterface|RegistrableInterface
     */
    public function getSource(string $key)
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     *
     * @return SourceInterface|RegistrableInterface
     */
    public function get(string $key): RegistrableInterface
    {
        if (!$this->isInitialized) {
            $this->init();
        }

        return parent::get($key);
    }

    /**
     * @return SourceInterface[]
     */
    public function all(): array
    {
        if (!$this->isInitialized) {
            $this->init();
        }

        return parent::all();
    }

    /**
     * @param ConcreteCallTaskTypeProviderInterface $taskProvider
     */
    public function registerProvider(ConcreteCallTaskTypeProviderInterface $taskProvider)
    {
        $this->concreteTypeProviders[$taskProvider->getType()] = $taskProvider;
    }

    /**
     * @param string $type
     *
     * @return ConcreteCallTaskTypeProviderInterface
     */
    public function getTypeProvider(string $type): ?ConcreteCallTaskTypeProviderInterface
    {
        return $this->concreteTypeProviders[$type];
    }

    /**
     * @return ConcreteCallTaskTypeProviderInterface[]
     */
    public function getAllTypeProviders(): array
    {
        return $this->concreteTypeProviders;
    }

    /**
     *
     */
    private function init(): void
    {
        foreach ($this->providerRegistry->all() as $provider) {
            foreach ($provider->getSources() as $source) {
                $this->add($source);
            }
        }

        $this->isInitialized = true;
    }
}
