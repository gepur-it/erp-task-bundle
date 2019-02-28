<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 26.07.18
 */

namespace GepurIt\CallTaskBundle\ActionProcessor;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Class ActionHandlerInterface
 * @package GepurIt\CallTaskBundle\ActionProcessor
 */
interface ActionHandlerInterface
{
    /**
     * @return string
     */
    public function getSupportedAction(): string;

    /**
     * @param ActionInterface   $action
     * @param CallTaskInterface $callTask
     *
     * @return ActionInterface
     */
    public function handle(ActionInterface $action, CallTaskInterface $callTask): ActionInterface;
}
