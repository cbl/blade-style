<?php

namespace BladeStyle\Compiler;

class CssCompiler extends Compiler
{
    /**
     * Compile style string.
     *
     * @param string $style
     * @return void
     */
    protected function compileString($style)
    {
        return $style;
    }
}
