<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 18.05.18
 */

namespace GepurIt\ErpTaskBundle\ActionProcessor;

use GepurIt\ErpTaskBundle\Contract\ErpTaskInterface;

/**
 * Interface ConcreteTypeActionProcessorInterface
  */
interface ActionProcessorInterface
{
    /**
     * @param ActionInterface  $action
     * @param ErpTaskInterface $callTask
     *
     * @return ActionInterface
     */
    public function processAction(ActionInterface $action, ErpTaskInterface $callTask): ActionInterface;

    /**
     * @return array
     */
    public function getSupportedActions(): array;

    /**
     * @param ActionInterface $action
     * @return bool
     */
    public function hasSupport(ActionInterface $action): bool;

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasSupportByClass(string $class): bool;
}
