<?php

namespace BladeStyle\Engines;

use BladeStyle\Components\StylesComponent;
use Illuminate\Support\Str;
use BladeStyle\Support\Style;
use Facade\Ignition\Views\Engines\CompilerEngine;

class BladeCompilerEngine extends CompilerEngine
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
        return $this->addStyles(
            parent::get($path, $data)
        );
    }

    /**
     * Add styles to the "@xStyles" directive.
     *
     * @param string $result
     * @return void
     */
    protected function addStyles(string $result)
    {
        if (!Str::contains($result, StylesComponent::PLACEHOLDER)) {
            return $result;
        }

        return Str::replaceFirst(
            StylesComponent::PLACEHOLDER,
            Style::render() . StylesComponent::PLACEHOLDER,
            $result
        );
    }
}
