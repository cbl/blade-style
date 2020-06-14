<?php

namespace BladeStyle\Directives;

use Illuminate\Support\Facades\File;

class WatchStyles
{
    /**
     * Compile @watchStyles
     *
     * @param string $expression
     * @return string
     */
    public function compile($expression)
    {
        return $this->errorModal() . $this->watchScript();
    }

    /**
     * Get watch script.
     *
     * @return string
     */
    protected function watchScript()
    {
        $script = File::get(__DIR__ . '/../../scripts/watch.js');

        return "<script>{$script}</script>";
    }

    /**
     * Get error modal.
     *
     * @return string
     */
    protected function errorModal()
    {
        return "<div id=\"blade-style-error\" style=\"display:none;\"><div></div></div>";
    }
}
