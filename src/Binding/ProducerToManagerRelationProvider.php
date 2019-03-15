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
            foreach ($provider->getSources() as $source) {
                $tempSource             = [];
                $tempSource['name']     = $source->getName();
                $tempSource['label']    = $source->getLabel();
                $tempSource['type']     = $provider->getType();
                $relations              = array_filter(
                    $managerHasCTSs,
                    function (ManagerHasTaskProducer $hasCTS) use ($source) {
                        return ($source->getName() === $hasCTS->getSourceName());
                    }
                );
                $tempSource['managers'] = array_values($relations);

                $result[$source->getName()] = $tempSource;
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
                return $item['sourceType'].'-'.$item['sourceName'];
            },
            $relationsToStore
        );
        $existsRelations = $repository->findByUser($managerId);

        //remove unnecessary relations
        foreach ($existsRelations as $relation) {
            if (!in_array($relation->getSourceType().'-'.$relation->getSourceName(), $index)) {
                $this->entityManager->remove($relation);
            }
        }

        $result = [];
        //create or update needed relations
        foreach ($relationsToStore as $relationToStore) {
            $relation = $repository->findOneBy(
                [
                    'userId'     => $managerId,
                    'sourceName' => $relationToStore['sourceName'],
                    'sourceType' => $relationToStore['sourceType'],
                ]
            );

            if (null === $relation) {
                $relation =
                    new ManagerHasTaskProducer($managerId, $relationToStore['sourceName'], $relationToStore['sourceType']);
                $this->entityManager->persist($relation);
            }

            $relation->setPriority((int)$relationToStore['priority']);
            $result[] = $relation;
        }

        return $result;
    }
}
