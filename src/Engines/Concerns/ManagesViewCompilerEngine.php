<?php

namespace BladeStyle\Engines\Concerns;

use BladeStyle\Support\Style;

trait ManagesViewCompilerEngine
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param string $path
     * @param array  $data
     *
     * @return string
     */
    public function get($path, array $data = [])
    {
        return Style::include(
            parent::get($path, $data)
        );
    }
}
