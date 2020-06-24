<?php

namespace BladeStyle\Engines;

use BladeStyle\Contracts\CssMinifier;

class MinifierEngine
{
    /**
     * Minifier.
     *
     * @var \BladeStyle\Contracts\CssMinifier
     */
    protected $minifier;

    /**
     * Create new MinifierEngine instance.
     *
     * @param CssMinifier $minifier
     */
    public function __construct(CssMinifier $minifier)
    {
        $this->minifier = $minifier;
    }

    /**
     * Set minifier.
     *
     * @param CssMinifier $minifier
     * @return void
     */
    public function setMinifier(CssMinifier $minifier)
    {
        $this->minifier = $minifier;
    }

    /**
     * Minify css string
     *
     * @return string
     */
    public function minify(string $css)
    {
        return $this->minifier->minify($css);
    }
}
