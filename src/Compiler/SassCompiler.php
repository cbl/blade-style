<?php

namespace BladeStyle\Compiler;

class SassCompiler extends CompileAdapter
{
    /**
     * Compile sass string and store it to the given path.
     *
     * @param string $style
     * @param string $path
     * @return void
     */
    public function compile(string $style, string $path)
    {
        $error = shell_exec($this->getCompileCommand($style, $path));
        if (!$error) {
            return;
        }
        $error = json_decode($error);
        $this->throwStyleException($error->message, $error->line);
    }

    /**
     * Get compile command.
     *
     * @param string $style
     * @param string $path
     * @return void
     */
    public function getCompileCommand(string $style, string $path)
    {
        $compilerPath = __DIR__ . '/../../scripts/compile-sass.js';
        $oneLineStyle = str_replace("\n", '<br>', $style);
        return "/Users/helen/.nvm/versions/node/v12.16.3/bin/node $compilerPath '{$oneLineStyle}' --path={$path} 2>&1";
    }
}
