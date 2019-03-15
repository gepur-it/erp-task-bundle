<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.07.18
 */

namespace GepurIt\ErpTaskBundle\ActionProcessor;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Class ActionHandlerInterface
 * @package GepurIt\ErpTaskBundle\ActionProcessor
 */
interface ActionHandlerInterface
{
    /**
     * @return string
     */
    public function getSupportedAction(): string;

    /**
     * @param ActionInterface                                   $action
     * @param \GepurIt\ErpTaskBundle\Contract\ErpTaskInterface $callTask
     *
     * @return ActionInterface
     */
    public function handle(ActionInterface $action, ErpTaskInterface $callTask): ActionInterface;
}
