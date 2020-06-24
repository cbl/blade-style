<?php

namespace BladeStyle\Contracts;

interface CssMinifier
{
    /**
     * Minify css string.
     *
     * @return string
     */
    public function minify(string $css);
}
