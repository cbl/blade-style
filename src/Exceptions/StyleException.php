<?php

namespace BladeStyle\Exceptions;

use Exception;
use InvalidArgumentException;

class StyleException extends InvalidArgumentException
{
    /**
     * Create new FieldException instance.
     *
     * @param string $message
     * @param array $options
     * @param integer $code
     * @param Exception $previous
     */
    public function __construct($message = null, array $options = [], $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->file = $options['file'] ?? $this->file;
        $this->line = $options['line'] ?? $this->line;
    }
}
