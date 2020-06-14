<?php

namespace BladeStyle\Compiler;

interface CompilerInterface
{
    /**
     * Create new compiler instance.
     *
     * @param string $bladePath
     * @return void
     */
    public function __construct(string $bladePath = '');

    /**
     * Compile style string and store it to the given path.
     *
     * @param string $style
     * @param string $path
     * @return void
     */
    public function compile(string $style, string $path);


    /**
     * Make new compiler instance.
     *
     * @param string $bladePath
     * @return static
     */
    public function make(string $bladePath);
}
