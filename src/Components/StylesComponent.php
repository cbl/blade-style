<?php

namespace BladeStyle\Components;

use Illuminate\View\Component;

class StylesComponent extends Component
{
    const PLACEHOLDER = '<blade-styles></blade-styles>';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return static::PLACEHOLDER;
    }
}
