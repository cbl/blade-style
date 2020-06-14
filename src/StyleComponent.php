<?php

namespace BladeStyle;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Support\Facades\File;

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
    public $parentViewPath;

    /**
     * Create new StyleComponent instance.
     *
     * @param string $lang
     * @return void
     */
    public function __construct($lang = 'css')
    {
        $this->lang = $lang;

        $parentViewPath = $this->getParentViewPath();
    }

    /**
     * Get parent view path.
     *
     * @return string|null
     */
    protected function getParentViewPath()
    {
        $parentCompiledPath = $this->getParentCompiledPath();
        if (!$parentCompiledPath) {
            return;
        }
        return trim(Str::between(File::get($this->getParentCompiledPath()), 'PATH', 'ENDPATH'));
    }

    /**
     * Get parent compiled path.
     *
     * @return string|null
     */
    public function getParentCompiledPath()
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
