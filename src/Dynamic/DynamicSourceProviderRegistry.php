<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 28.02.19
 */

namespace GepurIt\ErpTaskBundle\Dynamic;

/**
 * Class DynamicSourceProviderRegistry
 * @package GepurIt\ErpTaskBundle\Dynamic
 */
class DynamicSourceProviderRegistry
{
    /**
     * @var DynamicSourceProviderInterface[]
     */
    private $providers;

    public function add(DynamicSourceProviderInterface $sourceProvider)
    {
        $name = get_class($sourceProvider);
        $this->providers[$name] = $sourceProvider;
    }

    /**
     * @return DynamicSourceProviderInterface[]
     */
    public function all(): array
    {
        return $this->providers;
    }

    /**
     * @param string $name
     *
     * @return DynamicSourceProviderInterface
     */
    public function get(string $name)
    {
        return $this->providers[$name];
    }
}
