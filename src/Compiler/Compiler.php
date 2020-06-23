<?php

namespace BladeStyle\Compiler;

use Illuminate\View\Compilers\Compiler as ViewCompiler;
use Illuminate\View\Compilers\CompilerInterface;

abstract class Compiler extends ViewCompiler implements CompilerInterface
{
    /**
     * Compile style string.
     *
     * @param string|null $string
     * @return string
     */
    abstract public function compileString($string);

    /**
     * Compile the style at the given path.
     *
     * @param  string  $path
     * @return void
     */
    public function compile($path)
    {
        $contents = $this->compileString(
            $this->getRaw($path)
        );


        $this->files->put(
            $this->getCompiledPath($path),
            $contents
        );
    }

    /**
     * Get raw style from path.
     *
     * @param string $path
     * @return string|null
     */
    public function getRaw($path)
    {
        return $this->getStyleFromString(
            $this->files->get($path)
        );
    }

    /**
     * Get style from string.
     *
     * @param string|null $string
     * @return string
     */
    protected function getStyleFromString(string $string)
    {
        preg_match('/<x-style [^>]*>(.|\n)*?<\/x-style>/', $string, $matches);

        if (empty($matches)) {
            preg_match('/<x-style>(.|\n)*?<\/x-style>/', $string, $matches);
        }

        if (empty($matches)) {
            return;
        }

        return preg_replace('/<[^>]*>/', '', $matches[0]);
    }
}
