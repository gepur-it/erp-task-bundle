<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\ErpTaskBundle\CurrentTaskMarker;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Interface CurrentTaskMarkerInterface
 * @package GepurIt\ErpTaskBundle\CallTask
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
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     * @param string                                            $userId
     *
     * @return CurrentTaskMarkInterface
     */
    public function markTask(ErpTaskInterface $callTask, string $userId): CurrentTaskMarkInterface;

    /**
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     * @param bool                                              $unlock
     */
    public function unmarkTask(ErpTaskInterface $callTask, bool $unlock = true): void;

    /**
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     *
     * @return CurrentTaskMarkInterface|null
     */
    public function getMarkByTask(ErpTaskInterface $callTask): ?CurrentTaskMarkInterface;

    /**
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     * @param string                                            $userId
     */
    public function transferTaskMark(ErpTaskInterface $callTask, string $userId): void;
}
