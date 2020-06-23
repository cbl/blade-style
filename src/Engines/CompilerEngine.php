<?php

namespace BladeStyle\Engines;

use BladeStyle\StyleCompiler;
use Illuminate\Support\Facades\File;
use BladeStyle\Contracts\StyleEngine;
use Illuminate\View\Compilers\CompilerInterface;

class CompilerEngine implements StyleEngine
{
    /**
     * Create a new Blade view engine instance.
     *
     * @param  \BladeStyle\StyleCompiler  $compiler
     * @return void
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Get compiled style from the given path.
     *
     * @param string $path
     * @return void
     */
    public function get(string $path)
    {
        if ($this->compiler->isExpired($path)) {
            $this->compiler->compile($path);
        }

        return File::get($this->compiler->getCompiledPath($path));
    }
}
