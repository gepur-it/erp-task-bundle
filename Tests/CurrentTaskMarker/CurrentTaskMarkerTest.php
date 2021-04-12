<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 30.05.18
 * Time: 17:54
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\Tests\CurrentTaskMarker;

use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarker;
use GepurIt\ErpTaskBundle\TaskMarker\TaskMarkInterface;
use GepurIt\ErpTaskBundle\Entity\TaskMark;
use GepurIt\ErpTaskBundle\Exception\TaskMarkNotFoundException;
use GepurIt\ErpTaskBundle\Repository\ErpTaskMarkRepository;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestErpTask;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrentTaskMarkerTest
 * @package GepurIt\ErpTaskBundle\Tests\CurrentTaskMarker
 */
class CurrentTaskMarkerTest extends TestCase
{
    public function testGetTaskMark()
    {
        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('finOneByUserId')
            ->willReturn(new TaskMark('taskId', 'source', 'userId', 'clientId'));

        $taskMark = $currentTaskMarker->getTaskMark('userId');

        $this->assertInstanceOf(TaskMarkInterface::class, $taskMark);
    }

    public function testMarkTask()
    {
        $userId = 'userId';
        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskInterface|MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $callTask->expects($this->once())
            ->method('getTaskId')
            ->willReturn('taskId');

        $callTask->expects($this->once())
            ->method('getType')
            ->willReturn('source');

        $callTask->expects($this->once())
            ->method('setLockedBy')
            ->with($userId);

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(TaskMark::class));

        $currentTaskMarker->markTask($callTask, $userId);
    }

    public function testUnmarkTaskNotFind()
    {
        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskInterface|MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOneByTask')
            ->with($callTask)
            ->willReturn(null);

        $entityManager->expects($this->never())->method('remove');

        $currentTaskMarker->unmarkTask($callTask);
    }

    public function testUnmarkTask()
    {
        $callTaskId = '42';

        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskInterface|MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTask->expects($this->once())->method('setLockedBy')->with(null);

        $callTaskMark = new TaskMark($callTaskId, 'source', 'userId', 'clientId');
        $callTaskMarkRepository->expects($this->once())
            ->method('findOneByTask')
            ->with($callTask)
            ->willReturn($callTaskMark);

        $entityManager->expects($this->once())
            ->method('remove')
            ->with($callTaskMark);

        $currentTaskMarker->unmarkTask($callTask);
    }

    public function testUnmarkTaskNoLock()
    {
        $callTaskId = '42';

        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface|MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTask->expects($this->never())->method('setLockedBy');

        $callTaskMark = new TaskMark($callTaskId, 'source', 'userId', 'clientId');
        $callTaskMarkRepository->expects($this->once())
            ->method('findOneByTask')
            ->with($callTask)
            ->willReturn($callTaskMark);

        $entityManager->expects($this->once())
            ->method('remove')
            ->with($callTaskMark);

        $currentTaskMarker->unmarkTask($callTask, false);
    }

    public function testGetMarkByTask()
    {
        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository = $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(TaskMark::class)
            ->willReturn($callTaskMarkRepository);

//        $callTaskMarkRepository->expects($this->once())
//            ->method('findOneByTaskRelatively');

        $currentTaskMarker->getMarkByTask(new TestErpTask('id'));
    }

    public function testTransferTaskMarkException()
    {
        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository = $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(TaskMark::class)
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOneByTask')
            ->willReturn(null);

        $this->expectException(TaskMarkNotFoundException::class);

        $currentTaskMarker->transferTaskMark(new TestErpTask('id'), 'dummy');
    }

    public function testTransferTaskMark()
    {
        /**@var EntityManagerInterface|MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**@var ErpTaskMarkRepository|MockObject $callTaskMarkRepository */
        $callTaskMarkRepository = $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new TaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(TaskMark::class)
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOneByTask')
            ->willReturn(new TaskMark('ud', 'test' ,'dummy', 'clientId'));

        $currentTaskMarker->transferTaskMark(new TestErpTask('id'), 'dummy');
    }
}
