<?php

namespace BladeStyle\Compiler;

use BladeStyle\Exceptions\StyleException;

abstract class Compiler
{
    protected $style;
    protected $id;
    protected $path;
    protected $bladePath;

    abstract public function compile();

    public function __construct($style, $id, $path, $bladePath)
    {
        $this->style = $style;
        $this->id = $id;
        $this->path = $path;
        $this->bladePath = $bladePath;
    }

    public function throwStyleException($message, $line)
    {
        throw new StyleException($message, [
            'file' => $this->bladePath,
            'line' => blade_style_starts_at($this->bladePath) + $line
        ]);
    }
}
