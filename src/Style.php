<?php

namespace BladeStyle;

use Illuminate\View\View;
use BladeStyle\Engines\CompilerEngine;
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
     * @var \BladeStyle\Engines\CompilerEngine
     */
    protected $engine;

    /**
     * Create new style instance.
     *
     * @param string $path
     * @param \BladeStyle\Engines\CompilerEngine $compiler
     */
    public function __construct($path, CompilerEngine $engine)
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

    /**
     * Get compiler.
     *
     * @return \BladeStyle\Compiler\Compiler
     */
    public function getCompiler()
    {
        return $this->engine->getCompiler();
    }
}
