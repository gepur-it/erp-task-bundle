<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 31.05.18
 * Time: 10:20
 */

namespace GepurIt\CallTaskBundle\Tests\CallTaskSource;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\CallTaskBundle\CallTaskSource\CallTaskProvider;
use GepurIt\CallTaskBundle\CurrentTaskMarker\CurrentTaskMarkerInterface;
use GepurIt\CallTaskBundle\Dynamic\DynamicSourceProviderRegistry;
use GepurIt\CallTaskBundle\Entity\CallTaskMark;
use GepurIt\CallTaskBundle\Entity\ManagerHasCTS;
use GepurIt\CallTaskBundle\Entity\SourceTemplate;
use GepurIt\CallTaskBundle\Entity\SourceTemplateRelation;
use GepurIt\CallTaskBundle\Repository\ManagerHasCTSRepository;
use GepurIt\CallTaskBundle\Repository\SourceTemplateRepository;
use GepurIt\CallTaskBundle\Tests\Stubs\ConcreteTestProvider;
use GepurIt\CallTaskBundle\Tests\Stubs\TestCallTask;
use GepurIt\CallTaskBundle\Tests\Stubs\TestSource;
use GepurIt\CallTaskBundle\Tests\Stubs\TestSourceNullNext;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yawa20\RegistryBundle\Exception\KeyNotFoundRegistryException;

/**
 * Class CallTaskProviderTest
 * @package GepurIt\CallTaskBundle\Tests\CallTaskSource
 */
class CallTaskProviderTest extends TestCase
{
    public function testGetSourcesByUserIdException()
    {
        /**@var ManagerHasCTSRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasCTSRepository::class);
        /**@var ManagerHasCTS|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasCTS::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(ManagerHasCTS::class)
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
        /**@var ManagerHasCTSRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasCTSRepository::class);
        /**@var ManagerHasCTS|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasCTS::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(ManagerHasCTS::class)
            ->willReturn($hasCTSRepository);

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager
        ]);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $callTaskProvider->add(new TestSource());

        $managerHasCTS->expects($this->once())
            ->method('getSourceName')
            ->willReturn('test');

        $result = $callTaskProvider->getSourcesByUserId('userId');

        $this->assertInstanceOf(TestSource::class, array_shift($result));
    }

    public function testGetNextTaskForUser()
    {
        /**@var ManagerHasCTSRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasCTSRepository::class);
        /**@var ManagerHasCTS|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasCTS::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasCTS::class)
            ->willReturn($hasCTSRepository);

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            EntityManagerInterface::class => $entityManager,
        ]);

        $hasCTSRepository->expects($this->once())
            ->method('findByUser')
            ->willReturn([$managerHasCTS]);

        $callTaskProvider->add(new TestSource());

        $managerHasCTS->expects($this->once())
            ->method('getSourceName')
            ->willReturn('test');

        $result = $callTaskProvider->determineNextTask('userId');

        $this->assertInstanceOf(TestCallTask::class, $result);
    }

    public function testGetNextTaskForUserByDefaultTemplate()
    {
        /**@var ManagerHasCTSRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasCTSRepository::class);
        /** @var SourceTemplateRepository|\PHPUnit_Framework_MockObject_MockObject $templateRepository */
        $templateRepository = $this->createMock(SourceTemplateRepository::class);
        /**@var SourceTemplate|\PHPUnit_Framework_MockObject_MockObject $sourceTemplate */
        $sourceTemplate = $this->createMock(SourceTemplate::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasCTS::class)
            ->willReturn($hasCTSRepository);
        $entityManager->expects($this->at(1))
            ->method('getRepository')
            ->with(SourceTemplate::class)
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
        /**@var ManagerHasCTSRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasCTSRepository::class);
        /** @var SourceTemplateRepository|\PHPUnit_Framework_MockObject_MockObject $templateRepository */
        $templateRepository = $this->createMock(SourceTemplateRepository::class);
        /**@var SourceTemplate|\PHPUnit_Framework_MockObject_MockObject $sourceTemplate */
        $sourceTemplate = $this->createMock(SourceTemplate::class);
        /**@var SourceTemplateRelation|\PHPUnit_Framework_MockObject_MockObject $templateRelation */
        $templateRelation = $this->createMock(SourceTemplateRelation::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasCTS::class)
            ->willReturn($hasCTSRepository);
        $entityManager->expects($this->at(1))
            ->method('getRepository')
            ->with(SourceTemplate::class)
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

        $callTaskProvider->add(new TestSource());

        $callTaskProvider->determineNextTask('userId');
    }

    public function testGetNextTaskForUserNullNext()
    {
        /**@var ManagerHasCTSRepository|\PHPUnit_Framework_MockObject_MockObject $hasCTSRepository */
        $hasCTSRepository = $this->createMock(ManagerHasCTSRepository::class);
        /**@var ManagerHasCTS|\PHPUnit_Framework_MockObject_MockObject $managerHasCTS */
        $managerHasCTS = $this->createMock(ManagerHasCTS::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->at(0))
            ->method('getRepository')
            ->with(ManagerHasCTS::class)
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
        $this->assertInstanceOf(TestCallTask::class, $result);
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

    public function testAll()
    {
        /**@var DynamicSourceProviderRegistry|\PHPUnit_Framework_MockObject_MockObject $sourceProvider */
        $sourceProvider = $this->createMock(DynamicSourceProviderRegistry::class);

        $callTaskProvider = $this->createCallTaskProviderWithMockArgs([
            DynamicSourceProviderRegistry::class => $sourceProvider
        ]);

        $sourceProvider->expects($this->once())
            ->method('getSources')
            ->willReturn([new TestSource()]);

        $guessWho = $callTaskProvider->all();

        $this->assertInstanceOf(TestSource::class, array_shift($guessWho));
    }

    /**
     * @param array $args
     * @return CallTaskProvider
     */
    private function createCallTaskProviderWithMockArgs(array $args = []): CallTaskProvider
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
        /**@var DynamicSourceProviderRegistry|\PHPUnit_Framework_MockObject_MockObject $sourceProvider */
        $sourceProvider = (isset($args[DynamicSourceProviderRegistry::class]))
            ? $args[DynamicSourceProviderRegistry::class]
            : $this->createMock(DynamicSourceProviderRegistry::class);

        $callTaskProvider =
            new CallTaskProvider(
                $entityManager,
                $taskMarker,
                $eventDispatcher,
                $sourceProvider
            );

        return $callTaskProvider;
    }
}
