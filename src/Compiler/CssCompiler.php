<?php

namespace BladeStyle\Compiler;

use BladeStyle\Compiler\Compiler;
use Illuminate\Support\Facades\File;

class CssCompiler extends Compiler
{
    public function compile()
    {
        File::put($this->path, $this->style);
    }
}
