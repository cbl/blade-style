<?php

namespace BladeStyle\Directives;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;

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
        return $this->errorModal() . $this->watchScript($expression);
    }

    /**
     * Get watch script.
     *
     * @return string
     */
    protected function watchScript($expression)
    {
        $script = File::get(__DIR__ . '/../../scripts/watch.js');

        if ($expression == '') {
            throw new InvalidArgumentException('Missing style name for @watchStyles.');
        }

        return "<script>const styleName = '<?php echo {$expression}; ?>'\n{$script}</script>";
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
