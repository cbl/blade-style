<?php

namespace BladeStyle\Compiler;

use BladeStyle\Compiler\Compiler;
use BladeStyle\Exceptions\SassException;

class SassCompiler extends Compiler
{
    /**
     * Compile scss to file.
     *
     * @return void
     */
    public function compile()
    {
        $error = shell_exec($this->getCompileCommand());
        if (!$error) {
            return;
        }
        $error = json_decode($error);
        $this->throwStyleException($error->message, $error->line);
    }

    /**
     * Get compile command.
     *
     * @return void
     */
    public function getCompileCommand()
    {
        $compilerPath = __DIR__ . '/../../scripts/compile-sass.js';
        $oneLineStyle = str_replace("\n", '<br>', $this->style);
        return "/Users/helen/.nvm/versions/node/v12.16.3/bin/node $compilerPath '{$oneLineStyle}' --path={$this->path} 2>&1";
    }
}
