<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\ErpTaskBundle\TaskMarker;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Interface TaskMarkerInterface
 * @package GepurIt\ErpTaskBundle\CallTask
 */
interface TaskMarkerInterface
{
    /**
     * @param string $userId
     *
     * @return TaskMarkInterface|null
     */
    public function getTaskMark(string $userId): ?TaskMarkInterface;

    /**
     * @param ErpTaskInterface $callTask
     * @param string                                            $userId
     *
     * @return TaskMarkInterface
     */
    public function markTask(ErpTaskInterface $callTask, string $userId): TaskMarkInterface;

    /**
     * @param ErpTaskInterface $callTask
     * @param bool                                              $unlock
     */
    public function unmarkTask(ErpTaskInterface $callTask, bool $unlock = true): void;

    /**
     * @param ErpTaskInterface $callTask
     *
     * @return TaskMarkInterface|null
     */
    public function getMarkByTask(ErpTaskInterface $callTask): ?TaskMarkInterface;

    /**
     * @param ErpTaskInterface $callTask
     * @param string                                            $userId
     */
    public function transferTaskMark(ErpTaskInterface $callTask, string $userId): void;

    /**
     * @param string $groupKey
     *
     * @return TaskMarkInterface[]|iterable
     */
    public function getMarksByGroup(string $groupKey): iterable;
}
