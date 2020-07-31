<?php

namespace BladeStyle\Exceptions;

use Exception;
use ParseError;

class StyleException extends ParseError
{
    /**
     * Create new StyleException instance.
     *
     * @param string    $message
     * @param int       $line
     * @param int       $code
     * @param Exception $previous
     */
    public function __construct($message = null, int $line = 0, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        //$this->file = $file === null ? $this->file : $file;
        $this->line = $line ?: $this->line;
    }
}
