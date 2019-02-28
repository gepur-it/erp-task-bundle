<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.05.18
 */

namespace GepurIt\CallTaskBundle\CurrentTaskMarker;

/**
 * Class CallTaskMarkerInterface
 * @package GepurIt\CallTaskBundle\CallTask
 */
interface CurrentTaskMarkInterface
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
