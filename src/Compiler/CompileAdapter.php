<?php

namespace BladeStyle\Compiler;

use BladeStyle\Exceptions\StyleException;

abstract class CompileAdapter implements CompilerInterface
{
    /**
     * Path to blade file.
     *
     * @var string
     */
    protected $bladePath;

    /**
     * Create new compiler instance.
     *
     * @param string $bladePath
     * @return void
     */
    public function __construct(string $bladePath = '')
    {
        $this->bladePath = $bladePath;
    }

    /**
     * Make new compiler instance.
     *
     * @param string $bladePath
     * @return static
     */
    public function make(string $bladePath)
    {
        return new static($bladePath);
    }

    /**
     * Throw style exception.
     *
     * @param string $message
     * @param integer $line
     * @return void
     * 
     * @throws \BladeStyle\Exceptions\StyleException
     */
    public function throwStyleException(string $message, int $line)
    {
        //throw new \Exception(blade_style_starts_at($this->bladePath) + $line);
        throw new StyleException($message, [
            'file' => $this->bladePath,
            'line' => blade_style_starts_at($this->bladePath) + $line
        ]);
    }
}
