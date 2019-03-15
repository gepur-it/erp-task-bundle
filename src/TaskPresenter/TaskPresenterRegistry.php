<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 04.07.18
 * Time: 12:40
 */

namespace GepurIt\ErpTaskBundle\TaskPresenter;

use Yawa20\RegistryBundle\Registry\SimpleRegistry;

/**
 * Class TaskPresenterRegistry
 * @package GepurIt\ErpTaskBundle\TaskPresenterRegistry
 * @method TaskPresenterInterface[] all(): array
 * @method TaskPresenterInterface get(string $key): TaskPresenterInterface
 */
class TaskPresenterRegistry extends SimpleRegistry
{
    public function __construct()
    {
        parent::__construct(TaskPresenterInterface::class);
    }
}
