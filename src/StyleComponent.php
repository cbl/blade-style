<?php

namespace BladeStyle;

use Illuminate\Support\Str;
use BladeStyle\Support\Style;
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
     * Style id.
     *
     * @var string
     */
    public $styleId;

    /**
     * Create new StyleComponent instance.
     *
     * @param string $lang
     * @return void
     */
    public function __construct($lang = 'css')
    {
        $this->lang = $lang;

        $parentPath = $this->getParentPath();
        if (!$parentPath) {
            return;
        }
        $this->styleId = Style::getIdFromPath($parentPath);
    }

    /**
     * Get parent path.
     *
     * @return void
     */
    protected function getParentPath()
    {
        foreach (debug_backtrace() as $trace) {
            if (!array_key_exists('file', $trace)) {
                continue;
            }
            if (Str::startsWith($trace['file'], storage_path('framework/views'))) {
                return $trace['file'];
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('blade-style::style');
    }
}
