<?php

namespace App\Facades;

use App\Core\Facade;
use App\Core\View as CoreView;

/**
 * @method static CoreView with(string|array $key, mixed $value = null)
 * @method static CoreView layout(string $layout)
 * @method static void component(string $name, array $data = [])
 * @method static void render(string $view)
 *
 * @see \App\Core\View
 */
class View extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return CoreView::class;
    }
}
