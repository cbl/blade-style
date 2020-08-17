<?php

namespace BladeStyle\Components;

use BladeStyle\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class StyleComponent extends Component
{
    /**
     * Style language.
     *
     * @var string
     */
    public $lang;

    /**
     * Style factory.
     *
     * @var \BladeStyle\Factory
     */
    public $style;

    /**
     * Create new StyleComponent instance.
     *
     * @param Factory     $factory
     * @param string|null $lang
     *
     * @return void
     */
    public function __construct(Factory $style, $lang = null)
    {
        $this->lang = $lang ?: config('style.default_lang');
        $this->style = $style;

        $this->makeStyle();
    }

    /**
     * Set style instance.
     *
     * @return void
     */
    protected function makeStyle()
    {
        $path = $this->getPathFromTrace();

        if (! $path) {
            return;
        }

        // Making a style instance. The factory will add the style to the stack
        // so it can be included.
        $this->style->make($path);
    }

    /**
     * Get path from trace.
     *
     * @return void
     */
    protected function getPathFromTrace()
    {
        foreach (debug_backtrace() as $trace) {
            if (! array_key_exists('file', $trace)) {
                continue;
            }

            if (! Str::startsWith($trace['file'], config('view.compiled'))) {
                continue;
            }

            return $this->getPathFromCompiled($trace['file']);
        }
    }

    /**
     * Get view path from compiled instance.
     *
     * @param string $compiled
     *
     * @return string
     */
    protected function getPathFromCompiled($compiled)
    {
        return trim(Str::between(File::get($compiled), '/**PATH', 'ENDPATH**/'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render()
    {
        //
    }
}
