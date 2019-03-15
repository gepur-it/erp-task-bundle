<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 30.05.18
 * Time: 17:54
 */

namespace GepurIt\ErpTaskBundle\Tests\CurrentTaskMarker;


use Doctrine\ORM\EntityManagerInterface;
use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;
use GepurIt\ErpTaskBundle\CurrentTaskMarker\CurrentTaskMarker;
use GepurIt\ErpTaskBundle\CurrentTaskMarker\CurrentTaskMarkInterface;
use GepurIt\ErpTaskBundle\Entity\CallTaskMark;
use GepurIt\ErpTaskBundle\Exception\TaskMarkNotFoundException;
use GepurIt\ErpTaskBundle\Repository\ErpTaskMarkRepository;
use GepurIt\ErpTaskBundle\Tests\Stubs\TestErpTask;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrentTaskMarkerTest
 * @package GepurIt\ErpTaskBundle\Tests\CurrentTaskMarker
 */
class CurrentTaskMarkerTest extends TestCase
{
    public function testGetTaskMark()
    {
        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('finOneByUserId')
            ->willReturn(new CallTaskMark('taskId', 'source', 'userId', 'clientId'));

        $taskMark = $currentTaskMarker->getTaskMark('userId');

        $this->assertInstanceOf(CurrentTaskMarkInterface::class, $taskMark);
    }

    public function testMarkTask()
    {
        $userId = 'userId';
        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskInterface|\PHPUnit_Framework_MockObject_MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

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
            ->with($this->isInstanceOf(CallTaskMark::class));

        $currentTaskMarker->markTask($callTask, $userId);
    }

    public function testUnmarkTaskNotFind()
    {
        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskInterface|\PHPUnit_Framework_MockObject_MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOne')
            ->with($callTask)
            ->willReturn(null);

        $entityManager->expects($this->never())->method('remove');

        $currentTaskMarker->unmarkTask($callTask);
    }

    public function testUnmarkTask()
    {
        $callTaskId = '42';

        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var ErpTaskInterface|\PHPUnit_Framework_MockObject_MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTask->expects($this->once())->method('setLockedBy')->with(null);

        $callTaskMark = new CallTaskMark($callTaskId, 'source', 'userId', 'clientId');
        $callTaskMarkRepository->expects($this->once())
            ->method('findOne')
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

        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);

        /**@var \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface|\PHPUnit_Framework_MockObject_MockObject $taskMark */
        $callTask = $this->createMock(ErpTaskInterface::class);

        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository= $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($callTaskMarkRepository);

        $callTask->expects($this->never())->method('setLockedBy');

        $callTaskMark = new CallTaskMark($callTaskId, 'source', 'userId', 'clientId');
        $callTaskMarkRepository->expects($this->once())
            ->method('findOne')
            ->with($callTask)
            ->willReturn($callTaskMark);

        $entityManager->expects($this->once())
            ->method('remove')
            ->with($callTaskMark);

        $currentTaskMarker->unmarkTask($callTask, false);
    }

    public function testGetMarkByTask()
    {
        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository = $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(CallTaskMark::class)
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOneByTaskRelatively');

        $currentTaskMarker->getMarkByTask(new TestErpTask('id'));
    }

    public function testTransferTaskMarkException()
    {
        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository = $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(CallTaskMark::class)
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOne')
            ->willReturn(null);

        $this->expectException(TaskMarkNotFoundException::class);

        $currentTaskMarker->transferTaskMark(new TestErpTask('id'), 'dummy');
    }

    public function testTransferTaskMark()
    {
        /**@var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**@var ErpTaskMarkRepository|\PHPUnit_Framework_MockObject_MockObject $callTaskMarkRepository */
        $callTaskMarkRepository = $this->createMock(ErpTaskMarkRepository::class);

        $currentTaskMarker = new CurrentTaskMarker($entityManager);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(CallTaskMark::class)
            ->willReturn($callTaskMarkRepository);

        $callTaskMarkRepository->expects($this->once())
            ->method('findOne')
            ->willReturn(new CallTaskMark('ud', 'test' ,'dummy', 'clientId'));

        $currentTaskMarker->transferTaskMark(new TestErpTask('id'), 'dummy');
    }
}
