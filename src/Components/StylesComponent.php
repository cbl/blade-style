<?php

namespace BladeStyle\Components;

use Illuminate\View\Component;

class StylesComponent extends Component
{
    const PLACEHOLDER_OPEN = '<!-- START BLADE STYLES -->';
    const PLACEHOLDER_CLOSE = '<!-- END BLADE STYLES -->';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return static::PLACEHOLDER_OPEN . static::PLACEHOLDER_CLOSE;
    }
}
