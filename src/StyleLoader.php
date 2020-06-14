<?php

namespace BladeStyle;

use BladeStyle\StyleCompiler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Traits\ForwardsCalls;

class StyleLoader
{
    use ForwardsCalls;

    /**
     * Styles.
     *
     * @var array
     */
    protected $styles = [];

    /**
     * Style compiler.
     *
     * @var \BladeStyle\StyleCompiler
     */
    protected $compiler;

    /**
     * Create new StyleHandler instance.
     * 
     * @param \BladeStyle\StyleCompiler $compiler
     * @return void
     */
    public function __construct(StyleCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Get style.
     *
     * @param string $styleId
     * @return string
     */
    public function get(string $styleId)
    {
        if (array_key_exists($styleId, $this->styles)) {
            return $this->styles[$styleId];
        }

        $path = $this->compiler->getCompiledPath($styleId);

        if (!File::exists($path)) {
            return;
        }

        return $this->styles[$path] = File::get($path);
    }

    /**
     * Get all styles.
     *
     * @param boolean $fresh
     * @return array
     */
    public function all($fresh = false)
    {
        foreach ($this->getStyleFilesFrom(style_storage_path()) as $path) {
            $styleId = $this->getIdFromPath($path);

            if (array_key_exists($styleId, $this->styles) && !$fresh) {
                continue;
            }

            $this->styles[$styleId] = File::get($path);
        }

        return $this->styles;
    }

    /**
     * Reload styles.
     *
     * @return array
     */
    public function reload()
    {
        return $this->all(true);
    }

    /**
     * Get style files from path.
     *
     * @param string $path
     * @return array
     */
    protected function getStyleFilesFrom(string $path)
    {
        return glob($path . '/*.css');
    }

    /**
     * Get style tags.
     *
     * @param array $vars
     * @return array
     */
    public function getStyleTags()
    {
        $tags = [];

        if (config('app.debug')) {
            $debugStyles = File::get(__DIR__ . '/../styles/debug.css');

            $tags[] = "<style>{$debugStyles}</style>";
        }

        foreach ($this->styles as $path => $style) {
            $styleId = $this->getIdFromPath($path);
            $tags[] = "<style style:id=\"{$styleId}\">\n{$style}\n</style>";
        }

        return implode("\n", $tags);
    }

    /**
     * Get style id from path.
     *
     * @param string $path
     * @return string
     */
    public function getIdFromPath($path)
    {
        return style_id_from_path($path);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->compiler, $method, $parameters);
    }
}
