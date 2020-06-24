<?php

namespace BladeStyle\Engines;

use BladeStyle\Components\StylesComponent;
use Illuminate\Support\Str;
use BladeStyle\Support\Style;
use Facade\Ignition\Views\Engines\CompilerEngine;

class StyleCompilerEngine extends CompilerEngine
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array  $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return Style::include(
            parent::get($path, $data)
        );
    }
}
