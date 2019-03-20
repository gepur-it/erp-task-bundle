<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\ErpTaskBundle\Binding;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Entity\ManagerHasTaskProducer;
use GepurIt\ErpTaskBundle\Repository\ManagerHasProducerRepository;
use GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider;

/**
 * Class TaskToManagerRelationProvider
 * @package GepurIt\ErpTaskBundle\CallTaskSource
 */
class ProducerToManagerRelationProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var BaseTaskProvider
     */
    private $taskProvider;

    /**
     * TaskToManagerRelationProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param BaseTaskProvider       $taskProvider
     */
    public function __construct(EntityManagerInterface $entityManager, BaseTaskProvider $taskProvider)
    {
        $this->entityManager = $entityManager;
        $this->taskProvider  = $taskProvider;
    }

    /**
     * @return array
     */
    public function listSourcesWithManagers()
    {
        $result = [];
        /** @var ManagerHasProducerRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasTaskProducer::class);
        /** @var ManagerHasTaskProducer[] $ManagerCTSRelations */
        $managerHasCTSs = $repository->findAll();
        foreach ($this->taskProvider->getAllProviderTypes() as $provider) {
            foreach ($provider->getProducers() as $producer) {
                $tempSource             = [];
                $tempSource['name']     = $producer->getName();
                $tempSource['label']    = $producer->getLabel();
                $tempSource['type']     = $provider->getType();
                $relations              = array_filter(
                    $managerHasCTSs,
                    function (ManagerHasTaskProducer $hasCTS) use ($producer) {
                        return ($producer->getName() === $hasCTS->getProducerName());
                    }
                );
                $tempSource['managers'] = array_values($relations);

                $result[$producer->getName()] = $tempSource;
            }
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return ManagerHasTaskProducer[]
     */
    public function getManagerCTS(string $userId)
    {
        /** @var ManagerHasProducerRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasTaskProducer::class);

        return $repository->findByUser($userId);
    }

    /**
     * @param string $managerId
     * @param        $relationsToStore
     *
     * @return array
     */
    public function updateManagerCTS(string $managerId, array $relationsToStore)
    {
        /** @var ManagerHasProducerRepository $repository */
        $repository      = $this->entityManager->getRepository(ManagerHasTaskProducer::class);
        $index           = array_map(
            function ($item) {
                return $item['producerName'].'-'.$item['producerType'];
            },
            $relationsToStore
        );
        $existsRelations = $repository->findByUser($managerId);

        //remove unnecessary relations
        foreach ($existsRelations as $relation) {
            if (!in_array($relation->getProducerType().'-'.$relation->getProducerName(), $index)) {
                $this->entityManager->remove($relation);
            }
        }

        $result = [];
        //create or update needed relations
        foreach ($relationsToStore as $relationToStore) {
            $relation = $repository->findOneBy(
                [
                    'userId'     => $managerId,
                    'producerName' => $relationToStore['producerName'],
                    'producerType' => $relationToStore['producerType'],
                ]
            );

            if (null === $relation) {
                $relation =
                    new ManagerHasTaskProducer($managerId, $relationToStore['producerName'], $relationToStore['producerType']);
                $this->entityManager->persist($relation);
            }

            $relation->setPriority((int)$relationToStore['priority']);
            $result[] = $relation;
        }

        return $result;
    }
}
