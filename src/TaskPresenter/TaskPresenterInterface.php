<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 08.06.18
 * Time: 15:39
 */

namespace GepurIt\CallTaskBundle\TaskPresenter;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Interface TaskPresenterInterface
 * @package GepurIt\CallTaskBundle\TaskPresenterRegistry
 */
interface TaskPresenterInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param CallTaskInterface $callTask
     * @return mixed
     */
    public function present(CallTaskInterface $callTask);

    /**
     * @param CallTaskInterface $callTask
     * @return TaskListItemPresentationInterface
     */
    public function presentForList(CallTaskInterface $callTask): TaskListItemPresentationInterface;
}
