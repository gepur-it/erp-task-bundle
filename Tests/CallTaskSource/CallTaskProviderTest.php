<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:20
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\CallTaskSource;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarkerInterface;
use GepurIt\ErpTaskBundle\Entity\TaskMark;
use GepurIt\ErpTaskBundle\Entity\ManagerHasTaskProducer;
use GepurIt\ErpTaskBundle\Entity\ProducersTemplate;
use GepurIt\ErpTaskBundle\Entity\ProducerTemplateRelation;
use GepurIt\ErpTaskBundle\Repository\ManagerHasProducerRepository;
use GepurIt\ErpTaskBundle\Repository\ProducerTemplateRepository;
use GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider;
use GepurIt\ErpTaskBundle\Tests\Stubs\ConcreteTestNullProvider;
use GepurIt\ErpTaskBundle\Tests\Stubs\ConcreteTestProvider;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestErpTask;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestTaskProducer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CallTaskProviderTest
 * @package GepurIt\ErpTaskBundle\Tests\CallTaskSource
 */
class CallTaskProviderTest extends TestCase
{
    public function testGetProducersByUserId()
    {
        /**@var ManagerHasProducerRepository|MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasTaskProducer::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager
        ]);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $callTaskProvider->registerProvider(new ConcreteTestProvider());

        $managerHasCTS->expects($this->once())
            ->method('getProducerName')
            ->willReturn('test');
        $managerHasCTS->expects($this->once())
            ->method('getProducerType')
            ->willReturn('test');

        $result = $callTaskProvider->getProducersByUserId('userId');

        $this->assertInstanceOf(TestTaskProducer::class, array_shift($result));
    }

    public function testGetNextTaskForUser()
    {
        /**@var ManagerHasProducerRepository|MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasTaskProducer::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager,
        ]);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $callTaskProvider->registerProvider(new ConcreteTestProvider());

        $managerHasCTS->expects($this->once())
            ->method('getProducerName')
            ->willReturn('test');
        $managerHasCTS->expects($this->once())
            ->method('getProducerType')
            ->willReturn('test');

        $result = $callTaskProvider->determineNextTask('userId');

        $this->assertInstanceOf(TestErpTask::class, $result);
    }

    public function testGetNextTaskForUserByDefaultTemplate()
    {
        /**@var ManagerHasProducerRepository|MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /** @var ProducerTemplateRepository|MockObject $templateRepository */
        $templateRepository = $this->createMock(ProducerTemplateRepository::class);
        /**@var ProducersTemplate|MockObject $sourceTemplate */
        $sourceTemplate = $this->createMock(ProducersTemplate::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);
        $entityManager->expects($this->at(1))
            ->method('getRepository')
            ->with(ProducersTemplate::class)
            ->willReturn($templateRepository);

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager
        ]);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([]);

        $templateRepository->expects($this->once())
            ->method('findOneByUserId')
            ->willReturn(null);

        $templateRepository->expects($this->once())
            ->method('getDefault')
            ->willReturn($sourceTemplate);

        $sourceTemplate->expects($this->once())
            ->method('getRelations')
            ->willReturn([]);

        $callTaskProvider->determineNextTask('userId');
    }

    public function testGetNextTaskForUserByTemplate()
    {
        /**@var ManagerHasProducerRepository|MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /** @var ProducerTemplateRepository|MockObject $templateRepository */
        $templateRepository = $this->createMock(ProducerTemplateRepository::class);
        /**@var ProducersTemplate|MockObject $sourceTemplate */
        $sourceTemplate = $this->createMock(ProducersTemplate::class);
        /**@var ProducerTemplateRelation|MockObject $templateRelation */
        $templateRelation = $this->createMock(ProducerTemplateRelation::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);
        $entityManager->expects($this->exactly(1))
            ->id('1')
            ->method('getRepository')
            ->with(ProducersTemplate::class)
            ->willReturn($templateRepository);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([]);

        $templateRepository->expects($this->once())
            ->method('findOneByUserId')
            ->willReturn(null);

        $templateRepository->expects($this->once())
            ->method('getDefault')
            ->willReturn($sourceTemplate);

        $sourceTemplate->expects($this->once())
            ->method('getRelations')
            ->willReturn([$templateRelation]);

        $templateRelation->expects($this->once())
            ->method('getProducerName')
            ->willReturn('test');
        $templateRelation->expects($this->once())
            ->method('getProducerType')
            ->willReturn('test');

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager,
        ]);

        $callTaskProvider->registerProvider(new ConcreteTestProvider());

        $callTaskProvider->determineNextTask('userId');
    }

    public function testGetNextTaskForUserNullNext()
    {
        /**@var ManagerHasProducerRepository|MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasTaskProducer::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(1))
            ->method('getRepository')
            ->id('0')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $managerHasCTS->expects($this->once())
            ->method('getProducerName')
            ->willReturn('testNull');
        $managerHasCTS->expects($this->once())
            ->method('getProducerType')
            ->willReturn('testNull');

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager
        ]);

        $callTaskProvider->registerProvider(new ConcreteTestNullProvider());

        $result = $callTaskProvider->determineNextTask('userId');

        $this->assertNull($result);
    }

    public function testGetLockedByUser()
    {
        /**@var TaskMarkerInterface|MockObject $taskMarker */
        $taskMarker = $this->createMock(TaskMarkerInterface::class);
        /**@var TaskMark|MockObject $callTaskMark */
        $callTaskMark = $this->createMock(TaskMark::class);
        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            TaskMarkerInterface::class => $taskMarker
        ]);

        $taskMarker->expects($this->once())
            ->method('getTaskMark')
            ->willReturn($callTaskMark);
        $callTaskMark->expects($this->once())
            ->method('getType')
            ->willReturn('test');

        $callTaskProvider->registerProvider(new ConcreteTestProvider());
        $result = $callTaskProvider->getLockedByUser('userId');
        $this->assertInstanceOf(TestErpTask::class, $result);
    }

    public function testGetLockedByUserNull()
    {
        /**@var TaskMarkerInterface|MockObject $taskMarker */
        $taskMarker = $this->createMock(TaskMarkerInterface::class);
        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            TaskMarkerInterface::class => $taskMarker
        ]);
        $taskMarker->expects($this->once())
            ->method('getTaskMark')
            ->willReturn(null);

        $result = $callTaskProvider->getLockedByUser('userId');

        $this->assertNull($result);
    }

    /**
     * @param array $args
     *
     * @return \GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider
     */
    private function createCallTaskProviderWithMockArgs(array $args = []): BaseTaskProvider
    {
        $entityManager = (isset($args[EntityManagerInterface::class]))
            ? $args[EntityManagerInterface::class]
            : $this->createMock(EntityManagerInterface::class);
        /**@var TaskMarkerInterface|MockObject $taskMarker */
        $taskMarker = (isset($args[TaskMarkerInterface::class]))
            ? $args[TaskMarkerInterface::class]
            : $this->createMock(TaskMarkerInterface::class);
        /**@var EventDispatcherInterface|MockObject $eventDispatcher */
        $eventDispatcher = (isset($args[EventDispatcherInterface::class]))
            ? $args[EventDispatcherInterface::class]
            : $this->createMock(EventDispatcherInterface::class);

        $callTaskProvider =
            new BaseTaskProvider(
                $entityManager,
                $taskMarker,
                $eventDispatcher
            );

        return $callTaskProvider;
    }
}
