<?php

namespace BladeStyle\Minifier;

use BladeStyle\Contracts\CssMinifier;
use MatthiasMullie\Minify;

class MullieMinifier implements CssMinifier
{
    /**
     * Minify css string.
     *
     * @param string $css
     *
     * @return string
     */
    public function minify(string $css)
    {
        $mullie = $this->getMullieMinifier();

        $mullie->add($css);

        return $mullie->minify();
    }

    /**
     * Get Matthias mullie css minifier.
     *
     * @see https://github.com/matthiasmullie/minify
     *
     * @return MatthiasMullie\Minify\Css
     */
    public function getMullieMinifier()
    {
        return new Minify\CSS();
    }
}
