<?php

namespace BladeStyle;

use Illuminate\View\View;
use BladeStyle\Contracts\StyleEngine;
use BladeStyle\Contracts\StyleCompiler;

class Style
{
    /**
     * Path where style is located.
     *
     * @var string
     */
    protected $path;

    /**
     * Style engine.
     *
     * @var \BladeStyle\Contracts\StyleEngine
     */
    protected $engine;

    /**
     * Create new style instance.
     *
     * @param View $view
     * @param string $name
     * @param StyleCompiler $compiler
     */
    public function __construct($path, StyleEngine $engine)
    {
        $this->path = $path;
        $this->engine = $engine;
    }

    /**
     * Render style.
     *
     * @return string
     */
    public function render()
    {
        return $this->engine->get($this->path);
    }
}
