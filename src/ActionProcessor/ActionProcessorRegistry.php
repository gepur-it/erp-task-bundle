<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since: 18.05.18
 */

namespace GepurIt\ErpTaskBundle\ActionProcessor;

use Yawa20\RegistryBundle\Registry\SimpleRegistry;

/**
 * Class ActionProcessorRegistry
 * @package GepurIt\ErpTaskBundle\ActionProcessorRegistry
 * @method ActionProcessorInterface get(string $type)
 * @method  ActionProcessorInterface[] all()
 */
class ActionProcessorRegistry extends SimpleRegistry
{
    public function __construct()
    {
        parent::__construct(ActionProcessorInterface::class);
    }
}
