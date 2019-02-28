<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\CallTaskBundle\CurrentTaskMarker;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Interface CurrentTaskMarkerInterface
 * @package GepurIt\CallTaskBundle\CallTask
 */
interface CurrentTaskMarkerInterface
{
    /**
     * @param string $userId
     *
     * @return CurrentTaskMarkInterface|null
     */
    public function getTaskMark(string $userId): ?CurrentTaskMarkInterface;

    /**
     * @param CallTaskInterface $callTask
     * @param string            $userId
     *
     * @return CurrentTaskMarkInterface
     */
    public function markTask(CallTaskInterface $callTask, string $userId): CurrentTaskMarkInterface;

    /**
     * @param CallTaskInterface $callTask
     * @param bool              $unlock
     */
    public function unmarkTask(CallTaskInterface $callTask, bool $unlock = true): void;

    /**
     * @param CallTaskInterface $callTask
     *
     * @return CurrentTaskMarkInterface|null
     */
    public function getMarkByTask(CallTaskInterface $callTask): ?CurrentTaskMarkInterface;

    /**
     * @param CallTaskInterface $callTask
     * @param string            $userId
     */
    public function transferTaskMark(CallTaskInterface $callTask, string $userId): void;
}
