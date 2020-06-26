<?php

namespace BladeStyle\Exceptions;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\File;

class StyleException extends InvalidArgumentException
{
    /**
     * Create new FieldException instance.
     *
     * @param string $message
     * @param string $file
     * @param int $line
     * @param integer $code
     * @param Exception $previous
     */
    public function __construct($message = null, string $file = null, int $line = 0, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->file = $file === null ? $this->file : $file;
        $this->line = $line;
    }
}
