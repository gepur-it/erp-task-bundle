<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:20
 */

namespace GepurIt\ErpTaskBundle\Tests\CallTaskSource;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\CurrentTaskMarker\CurrentTaskMarkerInterface;
use GepurIt\ErpTaskBundle\Entity\CallTaskMark;
use GepurIt\ErpTaskBundle\Entity\ManagerHasTaskProducer;
use GepurIt\ErpTaskBundle\Entity\ProducersTemplate;
use GepurIt\ErpTaskBundle\Entity\ProducerTemplateRelation;
use GepurIt\ErpTaskBundle\Repository\ManagerHasProducerRepository;
use GepurIt\ErpTaskBundle\Repository\ProducerTemplateRepository;
use GepurIt\ErpTaskBundle\TaskProvider\BaseTaskProvider;
use GepurIt\ErpTaskBundle\Tests\Stubs\ConcreteTestProvider;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestErpTask;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestSourceNullNext;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestTaskProducer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yawa20\RegistryBundle\Exception\KeyNotFoundRegistryException;

/**
 * Class CallTaskProviderTest
 * @package GepurIt\ErpTaskBundle\Tests\CallTaskSource
 */
class CallTaskProviderTest extends TestCase
{
    public function testGetSourcesByUserIdException()
    {
        /**@var ManagerHasProducerRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasTaskProducer::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);

        $callTaskProvider =$this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager
        ]);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $managerHasCTS->expects($this->once())
            ->method('getSourceName')
            ->willReturn('dummy');

        $this->expectException(KeyNotFoundRegistryException::class);

        $callTaskProvider->getSourcesByUserId('userId');
    }

    public function testGetSourcesByUserId()
    {
        /**@var ManagerHasProducerRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
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

        $callTaskProvider->add(new TestTaskProducer());

        $managerHasCTS->expects($this->once())
            ->method('getSourceName')
            ->willReturn('test');

        $result = $callTaskProvider->getSourcesByUserId('userId');

        $this->assertInstanceOf(TestTaskProducer::class, array_shift($result));
    }

    public function testGetNextTaskForUser()
    {
        /**@var ManagerHasProducerRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
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

        $callTaskProvider->add(new TestTaskProducer());

        $managerHasCTS->expects($this->once())
            ->method('getSourceName')
            ->willReturn('test');

        $result = $callTaskProvider->determineNextTask('userId');

        $this->assertInstanceOf(TestErpTask::class, $result);
    }

    public function testGetNextTaskForUserByDefaultTemplate()
    {
        /**@var ManagerHasProducerRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /** @var ProducerTemplateRepository|\PHPUnit_Framework_MockObject_MockObject $templateRepository */
        $templateRepository = $this->createMock(ProducerTemplateRepository::class);
        /**@var ProducersTemplate|\PHPUnit_Framework_MockObject_MockObject $sourceTemplate */
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
        /**@var ManagerHasProducerRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /** @var ProducerTemplateRepository|\PHPUnit_Framework_MockObject_MockObject $templateRepository */
        $templateRepository = $this->createMock(ProducerTemplateRepository::class);
        /**@var ProducersTemplate|\PHPUnit_Framework_MockObject_MockObject $sourceTemplate */
        $sourceTemplate = $this->createMock(ProducersTemplate::class);
        /**@var ProducerTemplateRelation|\PHPUnit_Framework_MockObject_MockObject $templateRelation */
        $templateRelation = $this->createMock(ProducerTemplateRelation::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);
        $entityManager->expects($this->at(1))
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
            ->method('getSourceName')
            ->willReturn('test');

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager,
        ]);

        $callTaskProvider->add(new TestTaskProducer());

        $callTaskProvider->determineNextTask('userId');
    }

    public function testGetNextTaskForUserNullNext()
    {
        /**@var ManagerHasProducerRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasProducerRepository::class);
        /**@var ManagerHasTaskProducer|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasTaskProducer::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasTaskProducer::class)
            ->willReturn($hasCTSRepository);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $managerHasCTS->expects($this->once())
            ->method('getSourceName')
            ->willReturn('test');

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager
        ]);

        $callTaskProvider->add(new TestSourceNullNext());

        $result = $callTaskProvider->determineNextTask('userId');

        $this->assertNull($result);
    }

    public function testGetLockedByUser()
    {
        /**@var CurrentTaskMarkerInterface|\PHPUnit_Framework_MockObject_MockObject $taskMarker */
        $taskMarker = $this->createMock(CurrentTaskMarkerInterface::class);
        /**@var CallTaskMark|\PHPUnit_Framework_MockObject_MockObject $callTaskMark */
        $callTaskMark = $this->createMock(CallTaskMark::class);
        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            CurrentTaskMarkerInterface::class => $taskMarker
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
        /**@var CurrentTaskMarkerInterface|\PHPUnit_Framework_MockObject_MockObject $taskMarker */
        $taskMarker = $this->createMock(CurrentTaskMarkerInterface::class);
        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            CurrentTaskMarkerInterface::class => $taskMarker
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
        /**@var CurrentTaskMarkerInterface|\PHPUnit_Framework_MockObject_MockObject $taskMarker */
        $taskMarker = (isset($args[CurrentTaskMarkerInterface::class]))
            ? $args[CurrentTaskMarkerInterface::class]
            : $this->createMock(CurrentTaskMarkerInterface::class);
        /**@var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject $eventDispatcher */
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
