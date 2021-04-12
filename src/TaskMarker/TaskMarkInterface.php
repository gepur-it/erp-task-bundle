<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */
declare(strict_types=1);

namespace GepurIt\ErpTaskBundle\TaskMarker;

/**
 * Class CallTaskMarkerInterface
 */
interface TaskMarkInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getUserId(): string;

    /**
     * @return string
     */
    public function getTaskId(): string;

    /**
     * @param string $userId
     */
    public function transferTo(string $userId): void;
}
