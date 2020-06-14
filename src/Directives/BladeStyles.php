<?php

namespace BladeStyle\Directives;

class BladeStyles
{
    /**
     * Compile @bladeStyles
     *
     * @param string $expression
     * @return string
     */
    public function compile($expression)
    {
        return "<?php echo app('blade.style')->getStyleTags();?>";
    }
}
