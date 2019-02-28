<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.02.19
 */

namespace GepurIt\CallTaskBundle\CallTaskSource;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\CallTaskBundle\Entity\ManagerHasCTS;
use GepurIt\CallTaskBundle\Repository\ManagerHasCTSRepository;

/**
 * Class TaskToManagerRelationProvider
 * @package GepurIt\CallTaskBundle\CallTaskSource
 */
class SourceToManagerRelationProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CallTaskProvider
     */
    private $taskProvider;

    /**
     * TaskToManagerRelationProvider constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param CallTaskProvider       $taskProvider
     */
    public function __construct(EntityManagerInterface $entityManager, CallTaskProvider $taskProvider)
    {
        $this->entityManager = $entityManager;
        $this->taskProvider = $taskProvider;
    }

    /**
     * @return array
     */
    public function listSourcesWithManagers()
    {
        $sources    = $this->taskProvider->all();
        $result     = [];
        /** @var ManagerHasCTSRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasCTS::class);
        /** @var ManagerHasCTS[] $ManagerCTSRelations */
        $managerHasCTSs = $repository->findAll();
        foreach ($sources as $source) {
            $tempSource             = [];
            $tempSource['name']     = $source->getName();
            $tempSource['label']    = $source->getLabel();
            $relations              = array_filter(
                $managerHasCTSs,
                function (ManagerHasCTS $hasCTS) use ($source) {
                    return ($source->getName() === $hasCTS->getSourceName());
                }
            );
            $tempSource['managers'] = array_values($relations);

            $result[$source->getName()] = $tempSource;
        }

        return $result;
    }

    /**
     * @param string $userId
     *
     * @return ManagerHasCTS[]
     */
    public function getManagerCTS(string $userId)
    {
        /** @var ManagerHasCTSRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasCTS::class);

        return $repository->findByUser($userId);
    }

    /**
     * @param string $managerId
     * @param        $relationsToStore
     *
     * @return array
     */
    public function updateManagerCTS(string $managerId, array  $relationsToStore)
    {
        /** @var ManagerHasCTSRepository $repository */
        $repository = $this->entityManager->getRepository(ManagerHasCTS::class);
        $names            = array_column($relationsToStore, 'sourceName');
        $existsRelations = $repository->findByUser($managerId);

        //remove unnecessary relations
        foreach ($existsRelations as $relation) {
            if (!in_array($relation->getSourceName(), $names)) {
                $this->entityManager->remove($relation);
            }
        }

        $result = [];
        //create or update needed relations
        foreach ($relationsToStore as $relationToStore) {
            $relation = $repository->findOneBy(
                [
                    'userId' => $managerId,
                    'sourceName' => $relationToStore['sourceName']
                ]
            );

            if (null === $relation) {
                $relation = new ManagerHasCTS($managerId, $relationToStore['sourceName']);
                $this->entityManager->persist($relation);
            }

            $relation->setPriority((int)$relationToStore['priority']);
            $result[] = $relation;
        }
        return $result;
    }
}
