<?php

namespace BladeStyle\Support;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BladeStyle\Factory
 */
class Style extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'style.factory';
    }
}
