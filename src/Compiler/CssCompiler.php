<?php

namespace BladeStyle\Compiler;

use Illuminate\Support\Facades\File;

class CssCompiler extends CompileAdapter
{
    /**
     * Compile style string and store it to the given path.
     *
     * @param string $style
     * @param string $path
     * @return void
     */
    public function compile(string $style, string $path)
    {
        File::put($path, $style);
    }
}
