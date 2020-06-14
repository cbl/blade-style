<?php

namespace BladeStyle\Support;

use BladeStyle\StyleLoader;
use Illuminate\Support\Facades\Facade;

/**
 * @see \BladeStyle\StyleLoader
 * @see \BladeStyle\StyleCompiler
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
        return 'blade.style';
    }
}
