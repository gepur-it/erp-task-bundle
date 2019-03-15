<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 08.06.18
 * Time: 15:39
 */

namespace GepurIt\ErpTaskBundle\TaskPresenter;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Interface TaskPresenterInterface
 * @package GepurIt\ErpTaskBundle\TaskPresenterRegistry
 */
interface TaskPresenterInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     *
     * @return mixed
     */
    public function present(ErpTaskInterface $callTask);

    /**
     * @param ErpTaskInterface $callTask
     *
     * @return TaskListItemPresentationInterface
     */
    public function presentForList(ErpTaskInterface $callTask): TaskListItemPresentationInterface;
}
