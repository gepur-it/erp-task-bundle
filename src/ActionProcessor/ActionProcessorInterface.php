<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 18.05.18
 */

namespace GepurIt\CallTaskBundle\ActionProcessor;

use GepurIt\CallTaskBundle\CallTask\CallTaskInterface;

/**
 * Interface ConcreteTypeActionProcessorInterface
 * @package GepurIt\CallTaskBundle\ActionProcessorRegistry
 */
interface ActionProcessorInterface
{
    /**
     * @param ActionInterface   $action
     * @param CallTaskInterface $callTask
     *
     * @return ActionInterface
     */
    public function processAction(ActionInterface $action, CallTaskInterface $callTask): ActionInterface;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function getSupportedActions(): array ;

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
