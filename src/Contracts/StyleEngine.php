<?php

namespace BladeStyle\Contracts;

interface StyleEngine
{
    /**
     * Get compiled style string from path.
     *
     * @param string $style
     * @return string
     */
    public function get(string $path);
}
